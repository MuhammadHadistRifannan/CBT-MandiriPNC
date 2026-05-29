<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Admin\ProdiController as AdminProdiController;
use App\Http\Controllers\Admin\SoalCbtController;
use App\Http\Controllers\Pengawas\ActivityLogController as PengawasActivityLogController;
use App\Http\Controllers\Pengawas\BroadcastController as PengawasBroadcastController;
use App\Http\Controllers\Pengawas\DashboardController as PengawasDashboardController;
use App\Http\Controllers\Pengawas\CheckInController as PengawasCheckInController;
use App\Http\Controllers\Users\Command\ProfileController;
use App\Http\Controllers\Users\Command\UjianController as UserUjianCommandController;
use App\Http\Controllers\Users\Command\UjianActivityController;
use App\Http\Controllers\Users\PaymentController;
use App\Http\Controllers\Users\Query\BroadcastMessageController;
use App\Http\Controllers\Users\Query\CetakIdentitasController;
use App\Http\Controllers\Users\Query\DokumenController;
use App\Http\Controllers\Users\Query\ProdiController;
use App\Http\Controllers\Users\Query\UjianController;
use App\Http\Middleware\RoleBasedMiddleware;
use App\UserRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', function () {
    return redirect()->route('prodi.pilih');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', fn () => view('home'))->name('home');
Route::get('/home/pengumuman', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('pengumuman.index');
Route::post('/home/pengumuman', [App\Http\Controllers\AnnouncementController::class, 'check'])
    ->middleware('throttle:6,1')
    ->name('pengumuman.check');

Route::post('/payment/notification', [PaymentController::class, 'notification'])
    ->name('payment.notification');

Route::middleware(['auth', RoleBasedMiddleware::class.':'.UserRole::User->value])->group(function () {
    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.pilih');
    Route::post('/prodi', [App\Http\Controllers\Users\Command\ProdiController::class, 'simpan'])->name('prodi.simpan');

    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::post('/dokumen', [App\Http\Controllers\Users\Command\DokumenController::class, 'simpan'])->name('dokumen.simpan');

    Route::get('/cetak-identitas', [CetakIdentitasController::class, 'index'])->name('cetak.identitas');
    Route::get('/helpdesk', fn () => view('helpdesk'))->name('helpdesk');

    Route::get('/portal-ujian', [UjianController::class, 'index'])->name('portal.ujian');
    Route::get('/ujian', [UjianController::class, 'show'])->name('ujian.show');
    Route::get('/ujian/status', [UjianController::class, 'status'])->name('ujian.status');
    Route::post('/ujian/start', [UserUjianCommandController::class, 'start'])->name('ujian.start');
    Route::post('/ujian/answers', [UserUjianCommandController::class, 'saveAnswer'])->name('ujian.answers.store');
    Route::post('/ujian/submit', [UserUjianCommandController::class, 'submit'])->name('ujian.submit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/upload-foto', [CetakIdentitasController::class, 'uploadFoto'])->name('cetak.upload-foto');

    Route::post('/payment/snap', [PaymentController::class, 'generateSnap'])->name('payment.snap');
    Route::post('/payment/sync', [PaymentController::class, 'sync'])->name('payment.sync');
    Route::get('/broadcast-messages', [BroadcastMessageController::class, 'index'])->name('participant.broadcast.index');
    Route::post('/broadcast-messages/{broadcastMessage}/dismiss', [BroadcastMessageController::class, 'dismiss'])->name('participant.broadcast.dismiss');
    Route::post('/ujian/activity', [UjianActivityController::class, 'store'])->name('participant.activity.store');
});

Route::prefix('admin')->middleware(['auth', RoleBasedMiddleware::class.':'.UserRole::Admin->value])->group(function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('dokumen', [AdminDokumenController::class, 'index'])->name('admin.dokumen');
    Route::get('dokumen/{dokumen}', [AdminDokumenController::class, 'show'])->name('admin.dokumen.show');
    Route::patch('dokumen/{dokumen}/review', [AdminDokumenController::class, 'review'])->name('admin.dokumen.review');
    Route::get('pengumuman', [AdminAnnouncementController::class, 'index'])->name('admin.pengumuman');
    Route::post('pengumuman/batches', [AdminAnnouncementController::class, 'storeBatch'])->name('admin.pengumuman.batches.store');
    Route::put('pengumuman/batches/{batch}', [AdminAnnouncementController::class, 'updateBatch'])->name('admin.pengumuman.batches.update');
    Route::post('pengumuman/batches/{batch}/generate', [AdminAnnouncementController::class, 'generate'])->name('admin.pengumuman.batches.generate');
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

Route::prefix('pengawas')->middleware(['auth', RoleBasedMiddleware::class.':'.UserRole::Pengawas->value])->group(function () {
    Route::get('dashboard', [PengawasDashboardController::class, 'index'])->name('pengawas.dashboard');
    Route::get('dashboard/data', [PengawasDashboardController::class, 'data'])->name('pengawas.dashboard.data');
    Route::patch('dashboard/ujian/{ujian}/flag', [PengawasDashboardController::class, 'updateFlag'])->name('pengawas.dashboard.flag');
    Route::patch('dashboard/ujian/{ujian}/timer', [PengawasDashboardController::class, 'updateTimer'])->name('pengawas.dashboard.timer');
    Route::get('check-in', [PengawasCheckInController::class, 'index'])->name('pengawas.check-in');
    Route::post('check-in/lookup', [PengawasCheckInController::class, 'lookup'])->name('pengawas.check-in.lookup');
    Route::post('check-in/{ujian}/confirm', [PengawasCheckInController::class, 'confirm'])->name('pengawas.check-in.confirm');
    Route::get('broadcast', [PengawasBroadcastController::class, 'index'])->name('pengawas.broadcast');
    Route::post('broadcast', [PengawasBroadcastController::class, 'store'])->name('pengawas.broadcast.store');
    Route::get('aktivitas', [PengawasActivityLogController::class, 'index'])->name('pengawas.activities');
    Route::get('aktivitas/data', [PengawasActivityLogController::class, 'data'])->name('pengawas.activities.data');
});

require __DIR__.'/auth.php';
