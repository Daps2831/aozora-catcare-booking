<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route untuk mengambil data booking per bulan
Route::get('/monthly-bookings', [BookingController::class, 'getMonthlyBookings']);