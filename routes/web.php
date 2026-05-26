<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\ProdiController as AdminProdiController;
use App\Http\Controllers\Admin\SoalCbtController;
use App\Http\Controllers\Users\Command\ProfileController;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\Query\CetakIdentitasController;
use App\Http\Controllers\Users\Query\DokumenController;
use App\Http\Controllers\Users\Query\ProdiController;
use App\Http\Controllers\Users\Query\UjianController;
use App\Http\Middleware\RoleBasedMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('prodi.pilih');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', fn () => view('home'))->name('home');

Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');

Route::middleware(['auth', RoleBasedMiddleware::class.':user'])->group(function () {
    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.pilih');
    Route::post('/prodi', [App\Http\Controllers\Users\Command\ProdiController::class, 'simpan'])->name('prodi.simpan');

    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::post('/dokumen', [App\Http\Controllers\Users\Command\DokumenController::class, 'simpan'])->name('dokumen.simpan');

    Route::get('/cetak-identitas', [CetakIdentitasController::class, 'index'])->name('cetak.identitas');
    Route::get('/helpdesk', fn () => view('helpdesk'))->name('helpdesk');

    Route::get('/portal-ujian', [UjianController::class, 'index'])->name('portal.ujian');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/upload-foto', [CetakIdentitasController::class, 'uploadFoto'])->name('cetak.upload-foto');

    Route::post('/payment/snap', [PaymentController::class, 'generateSnap'])->name('payment.snap');
});

Route::prefix('admin')->middleware(['auth', RoleBasedMiddleware::class.':admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('dokumen', [AdminDokumenController::class, 'index'])->name('admin.dokumen');
    Route::get('dokumen/{dokumen}', [AdminDokumenController::class, 'show'])->name('admin.dokumen.show');
    Route::patch('dokumen/{dokumen}/review', [AdminDokumenController::class, 'review'])->name('admin.dokumen.review');
    Route::get('program-studi', [AdminProdiController::class, 'index'])->name('admin.prodi');
    Route::post('program-studi', [AdminProdiController::class, 'store'])->name('admin.prodi.store');
    Route::put('program-studi/{prodi}', [AdminProdiController::class, 'update'])->name('admin.prodi.update');
    Route::delete('program-studi/{prodi}', [AdminProdiController::class, 'destroy'])->name('admin.prodi.destroy');
    Route::get('bank-soal', [SoalCbtController::class, 'index'])->name('admin.soal');
    Route::get('bank-soal/create', [SoalCbtController::class, 'create'])->name('admin.soal.create');
    Route::post('bank-soal', [SoalCbtController::class, 'store'])->name('admin.soal.store');
    Route::post('bank-soal/import-pdf', [SoalCbtController::class, 'importPdf'])->name('admin.soal.import-pdf');
    Route::get('bank-soal/{soal}/preview', [SoalCbtController::class, 'preview'])->name('admin.soal.preview');
    Route::get('bank-soal/{soal}/edit', [SoalCbtController::class, 'edit'])->name('admin.soal.edit');
    Route::put('bank-soal/{soal}', [SoalCbtController::class, 'update'])->name('admin.soal.update');
    Route::patch('bank-soal/{soal}/release', [SoalCbtController::class, 'release'])->name('admin.soal.release');
    Route::delete('bank-soal/{soal}', [SoalCbtController::class, 'destroy'])->name('admin.soal.destroy');
});

require __DIR__.'/auth.php';
