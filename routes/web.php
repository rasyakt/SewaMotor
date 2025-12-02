<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Owner\OwnerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Role-based routes
Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/motors', [AdminController::class, 'motors'])->name('admin.motors');
        Route::patch('/motors/{id}/verify', [AdminController::class, 'verifyMotor'])->name('admin.motors.verify');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/penyewaans', [AdminController::class, 'penyewaans'])->name('admin.penyewaans');
        Route::patch('/penyewaans/{id}/confirm', [AdminController::class, 'confirmPenyewaan'])->name('admin.penyewaans.confirm');
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
            // Resource CRUD routes
            Route::resource('crud-users', App\Http\Controllers\UserController::class);
            Route::resource('crud-motors', App\Http\Controllers\MotorController::class);
            Route::resource('crud-tarif-rentals', App\Http\Controllers\TarifRentalController::class);
            Route::resource('crud-penyewaans', App\Http\Controllers\PenyewaanController::class);
            Route::resource('crud-transaksis', App\Http\Controllers\TransaksiController::class);
    });

    // Owner routes
    Route::prefix('owner')->middleware('role:pemilik')->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
        Route::get('/motors', [OwnerController::class, 'motors'])->name('owner.motors');
        Route::get('/motors/create', [OwnerController::class, 'createMotor'])->name('owner.motors.create');
        Route::post('/motors', [OwnerController::class, 'storeMotor'])->name('owner.motors.store');
        Route::get('/revenue', [OwnerController::class, 'revenue'])->name('owner.revenue');
    });

    // Customer routes
    Route::prefix('customer')->middleware('role:penyewa')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
        Route::get('/motors', [CustomerController::class, 'motors'])->name('customer.motors');
        Route::get('/motors/{id}', [CustomerController::class, 'showMotor'])->name('customer.motors.show');
        Route::post('/motors/{id}/book', [CustomerController::class, 'bookMotor'])->name('customer.motors.book');
        Route::get('/bookings/history', [CustomerController::class, 'bookingHistory'])->name('customer.bookings.history');
        Route::get('/payments/{id}', [CustomerController::class, 'payment'])->name('customer.payments.create');
        Route::post('/payments/{id}', [CustomerController::class, 'processPayment'])->name('customer.payments.process');
    });
});

// Redirect after login based on role
// Route::get('/home', function () {
//     $user = auth()->user();
    
//     switch ($user->role) {
//         case 'admin':
//             return redirect()->route('admin.dashboard');
//         case 'pemilik':
//             return redirect()->route('owner.dashboard');
//         case 'penyewa':
//             return redirect()->route('customer.dashboard');
//         default:
//             return redirect('/');
//     }
// })->name('home');

// Di routes/web.php - update home route
Route::get('/home', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect('/login');
    }
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'pemilik':
            return redirect()->route('owner.dashboard');
        case 'penyewa':
            return redirect()->route('customer.dashboard');
        default:
            return redirect('/');
    }
})->name('home');