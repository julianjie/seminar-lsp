<?php
use App\Http\Controllers\Admin\AccountVerificationController;
use App\Http\Controllers\AccountStatusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SeminarController;

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
    });