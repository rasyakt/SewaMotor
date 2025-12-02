<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MotorController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Motors
    Route::get('/motors', [MotorController::class, 'index']);
    Route::post('/owner/motors', [MotorController::class, 'store']);
    Route::get('/motors/{id}', [MotorController::class, 'show']);

    // Bookings
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/history', [BookingController::class, 'history']);

    // Payments
    Route::post('/payments', [PaymentController::class, 'store']);

    // Reports
    Route::get('/owner/revenue', [ReportController::class, 'ownerRevenue']);
    Route::get('/admin/reports/revenue', [ReportController::class, 'adminRevenue']);

    // Admin only
    Route::middleware(['role:admin'])->group(function () {
        Route::patch('/admin/motors/{id}/verify', [MotorController::class, 'verify']);
        Route::patch('/admin/bookings/{id}/confirm', [BookingController::class, 'confirm']);
    });
});