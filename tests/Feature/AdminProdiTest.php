<?php

namespace Tests\Feature;

use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProdiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_database_prodi_and_can_filter_it(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $matched = $this->createProdi([
            'nama_prodi' => 'Teknologi Rekayasa Multimedia',
            'tingkat' => 'd4',
            'jurusan' => 'Komputer dan Bisnis',
        ]);
        $this->createProdi([
            'nama_prodi' => 'Teknik Mesin',
            'tingkat' => 'd3',
            'jurusan' => 'Teknik',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.prodi', ['tingkat' => 'd4', 'search' => 'Multimedia']))
            ->assertOk()
            ->assertSee($matched->nama_prodi)
            ->assertDontSee('Teknik Mesin');
    }

    public function test_admin_can_create_update_and_delete_prodi(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);

        $this->actingAs($admin)
            ->post(route('admin.prodi.store'), $this->validPayload())
            ->assertRedirect(route('admin.prodi'));

        $prodi = Prodi::query()->firstOrFail();

        $this->assertDatabaseHas('prodi', [
            'id' => $prodi->id,
            'nama_prodi' => 'Teknologi Rekayasa Multimedia',
            'jurusan' => 'Komputer dan Bisnis',
            'keketatan' => 0.42,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.prodi.update', $prodi), [
                ...$this->validPayload(),
                'daya_tampung' => 140,
                'keketatan_persen' => 38,
            ])
            ->assertRedirect(route('admin.prodi'));

        $this->assertDatabaseHas('prodi', [
            'id' => $prodi->id,
            'daya_tampung' => 140,
            'keketatan' => 0.38,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.prodi.destroy', $prodi))
            ->assertRedirect(route('admin.prodi'));

        $this->assertDatabaseMissing('prodi', ['id' => $prodi->id]);
    }

    public function test_admin_cannot_delete_prodi_already_selected_by_participant(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $participant = User::factory()->create(['role' => UserRole::User->value]);
        $primary = $this->createProdi(['nama_prodi' => 'Teknik Informatika']);
        $backup = $this->createProdi(['nama_prodi' => 'Akuntansi']);

        PilihanProdi::create([
            'user_id' => $participant->id,
            'pilihan_1' => $primary->id,
            'pilihan_2' => $backup->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.prodi.destroy', $primary))
            ->assertRedirect(route('admin.prodi'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('prodi', ['id' => $primary->id]);
    }

    public function test_admin_cannot_create_duplicate_prodi_name(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $this->createProdi(['nama_prodi' => 'Teknik Informatika']);

        $this->actingAs($admin)
            ->post(route('admin.prodi.store'), [
                ...$this->validPayload(),
                'nama_prodi' => 'Teknik Informatika',
                'tingkat' => 'd3',
                'jurusan' => 'Jurusan Berbeda',
            ])
            ->assertSessionHasErrors('nama_prodi');
    }

    private function validPayload(): array
    {
        return [
            'nama_prodi' => 'Teknologi Rekayasa Multimedia',
            'tingkat' => 'd4',
            'jurusan' => 'Komputer dan Bisnis',
            'peminat' => 845,
            'daya_tampung' => 120,
            'kuota' => 80,
            'keketatan_persen' => 42,
        ];
    }

    private function createProdi(array $overrides = []): Prodi
    {
        $payload = $this->validPayload();
        unset($payload['keketatan_persen']);

        return Prodi::create([
            ...$payload,
            'keketatan' => 0.42,
            ...$overrides,
        ]);
    }
}
