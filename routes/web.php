<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanCollectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CollectionReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('loan-products', \App\Http\Controllers\Admin\LoanProductController::class);
        Route::resource('savings-products', \App\Http\Controllers\Admin\SavingsProductController::class);
    });

    Route::resource('members', MemberController::class);
    Route::resource('loans', LoanController::class);
    Route::resource('collections', LoanCollectionController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('savings', SavingController::class)->only(['index', 'store', 'show']);
    Route::get('savings/{member}/download-statement', [SavingController::class, 'downloadStatement'])->name('savings.download-statement');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('loans', [ReportController::class, 'loans'])->name('loans');
        Route::get('collections', [CollectionReportController::class, 'index'])->name('collections');
        Route::get('savings', [ReportController::class, 'savings'])->name('savings');
    });

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});


Route::get('/optimize', function () {

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('optimize');

    return "Optimization completed!";
});