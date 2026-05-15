<?php

use App\Http\Controllers\Users\Command\ProfileController;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\Query\CetakIdentitasController;
use App\Http\Controllers\Users\Query\ProdiController;
use App\Http\Controllers\Users\Query\UjianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('prodi.pilih');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home' , fn() => view('home'))->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/prodi' , [ProdiController::class , 'index'])->name('prodi.pilih');
Route::post('/prodi' , [\App\Http\Controllers\Users\Command\ProdiController::class , 'simpan'])->name('prodi.simpan');

    Route::get('/cetak-identitas' , [CetakIdentitasController::class , 'index'])->name('cetak.identitas');
    Route::get('/helpdesk' , fn() => view('helpdesk'))->name('helpdesk');

    Route::get('/portal-ujian' , [UjianController::class  , 'index'])->name('portal.ujian');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::post('/payment/success' , [PaymentController::class , 'payment_success']);
});

require __DIR__.'/auth.php';
