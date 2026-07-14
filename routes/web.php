<?php
use App\Http\Controllers\Admin\AccountVerificationController;
use App\Http\Controllers\AccountStatusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SeminarController;
use App\Http\Controllers\Peserta\SeminarController as PesertaSeminarController;
use App\Http\Controllers\Peserta\SeminarRegistrationController;
use App\Http\Controllers\Admin\SeminarRegistrationVerificationController;
use App\Http\Controllers\Peserta\PaymentController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Peserta\AnnouncementController as PesertaAnnouncementController;

Route::view('/', 'welcome')->name('welcome');

Auth::routes();

Route::get(
    '/status-akun',
    [AccountStatusController::class, 'form']
)->name('account.status.form');

Route::post(
    '/status-akun',
    [AccountStatusController::class, 'check']
)->name('account.status.check');

Route::get(
    '/home',
    [HomeController::class, 'index']
)->middleware('auth')->name('home');

Route::middleware([
    'auth',
    'role:admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');

        Route::get(
            '/verifikasi-akun',
            [AccountVerificationController::class, 'index']
        )->name('account-verification.index');

        Route::patch(
            '/verifikasi-akun/{user}',
            [AccountVerificationController::class, 'update']
        )->name('account-verification.update');

        Route::resource(
            'seminars',
            SeminarController::class
        )->except('show');

        Route::get(
            '/verifikasi-pendaftaran',
            [SeminarRegistrationVerificationController::class, 'index']
        )->name('registration-verification.index');

        Route::patch(
            '/verifikasi-pendaftaran/{registration}',
            [SeminarRegistrationVerificationController::class, 'update']
        )->name('registration-verification.update');
        Route::get(
            '/verifikasi-pembayaran',
            [PaymentVerificationController::class, 'index']
        )->name('payment-verification.index');

        Route::patch(
            '/verifikasi-pembayaran/{payment}',
            [PaymentVerificationController::class, 'update']
        )->name('payment-verification.update');
        Route::resource(
            'announcements',
            AnnouncementController::class
        )->except('show');
    });

Route::middleware([
    'auth',
    'role:peserta',
    'approved',
])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {
        Route::get(
            '/dashboard',
            [PesertaDashboardController::class, 'index']
        )->name('dashboard');

        Route::get(
            '/seminars',
            [PesertaSeminarController::class, 'index']
        )->name('seminars.index');

        Route::get(
            '/seminars/{seminar}',
            [PesertaSeminarController::class, 'show']
        )->name('seminars.show');

        Route::post(
            '/seminars/{seminar}/daftar',
            [SeminarRegistrationController::class, 'store']
        )->name('registrations.store');

        Route::get(
            '/pendaftaran-seminar',
            [SeminarRegistrationController::class, 'index']
        )->name('registrations.index');

        Route::delete(
            '/pendaftaran-seminar/{registration}',
            [SeminarRegistrationController::class, 'destroy']
        )->name('registrations.destroy');
        Route::get(
            '/pembayaran',
            [PaymentController::class, 'index']
        )->name('payments.index');

        Route::get(
            '/pendaftaran-seminar/{registration}/pembayaran',
            [PaymentController::class, 'create']
        )->name('payments.create');

        Route::post(
            '/pendaftaran-seminar/{registration}/pembayaran',
            [PaymentController::class, 'store']
        )->name('payments.store');

        Route::get(
            '/pembayaran/{payment}/edit',
            [PaymentController::class, 'edit']
        )->name('payments.edit');

        Route::put(
            '/pembayaran/{payment}',
            [PaymentController::class, 'update']
        )->name('payments.update');
        Route::get(
            '/pengumuman',
            [PesertaAnnouncementController::class, 'index']
        )->name('announcements.index');

        Route::get(
            '/pengumuman/{announcement}',
            [PesertaAnnouncementController::class, 'show']
        )->name('announcements.show');
    });