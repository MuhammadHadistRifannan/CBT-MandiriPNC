<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'foto', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function pilihan()
    {
        return $this->hasOne(PilihanProdi::class, 'user_id');
    }

    public function peserta()
    {
        return $this->hasOne(Peserta::class, 'user_id');
    }

    public function dokumen()
    {
        return $this->hasOne(Dokumen::class, 'user_id');
    }

    public function billing()
    {
        return $this->hasOne(Billings::class, 'user_id');
    }

    public function ujians()
    {
        return $this->hasMany(Ujian::class, 'user_id');
    }

    public function ujian()
    {
        return $this->hasOne(Ujian::class, 'user_id');
    }

    public function ujianActivityLogs()
    {
        return $this->hasMany(UjianActivityLog::class, 'user_id');
    }
}
