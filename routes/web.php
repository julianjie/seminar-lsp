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
    });