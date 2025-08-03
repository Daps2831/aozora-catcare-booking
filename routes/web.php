<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KucingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;


// Halaman Beranda
Route::get('/', function () {
    return view('index');
});

// Halaman Kontak
Route::get('/contact', function () {
    return view('contact');
});

// Halaman Login
// Route untuk halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');

// Route untuk menangani login
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.submit');
    
// Halaman Register
// Route untuk menampilkan form registrasi
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register.form');

// Route untuk menangani form registrasi setelah di-submit
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('register.submit');

// Halaman Layanan
Route::get('/service', function () {
    return view('service');
});

// Halaman Informasi Layanan
Route::get('/pageinfo', function () {
    return view('pageinfo');
});

// Route untuk halaman dashboard user biasa
Route::middleware(['auth'])->get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

// Route untuk menampilkan profil
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

// Route untuk menampilkan form edit profil
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

// Route untuk memperbarui profil
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// Route untuk menampilkan form pendaftaran kucing
Route::get('/kucing/register', [KucingController::class, 'showForm'])->name('kucing.register');

// Route untuk menyimpan data kucing
Route::post('/kucing/register', [KucingController::class, 'store'])->name('kucing.store');

// Route untuk logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk menampilkan halaman edit kucing
Route::get('/kucing/{kucing}/edit', [KucingController::class, 'edit'])->name('kucing.edit');
Route::delete('kucing/{kucing}', [KucingController::class, 'destroy'])->name('kucing.destroy');

// Route untuk memproses pembaruan data kucing
Route::put('/kucing/{kucing}', [KucingController::class, 'update'])->name('kucing.update');

//t Route untuk Halaman Kalender
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index')->middleware('auth');

// TAMBAHKAN DUA ROUTE INI UNTUK PROSES BOOKING
Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Route untuk menampilkan halaman riwayat booking
Route::get('/customer/riwayat', [BookingController::class, 'riwayat'])->name('customer.riwayat');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    // Kelola Booking
    Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}/status', [App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    // booking list method edit update
    Route::get('/booking/{booking}/edit', [App\Http\Controllers\Admin\BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'update'])->name('booking.update');
    
    //lihat daftar booking jika klik tanggal
    Route::get('booking/by-date/{tanggal}', [\App\Http\Controllers\Admin\BookingController::class, 'byDate']);
    Route::get('/booking/{booking}', [App\Http\Controllers\Admin\BookingController::class, 'show'])->name('booking.show');
    //hapus booking
    Route::delete('/booking/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('booking.destroy');
    //acc booking
    Route::get('/booking/{booking}/acc', [App\Http\Controllers\Admin\BookingController::class, 'acc'])->name('booking.acc');
    Route::post('/booking/{booking}/acc', [App\Http\Controllers\Admin\BookingController::class, 'accProses'])->name('booking.accProses');
    Route::get('booking/by-date/{tanggal}', [App\Http\Controllers\Admin\BookingController::class, 'byDate'])->name('booking.by-date');
    //selesai booking
    Route::get('/booking/{id}/selesai', [App\Http\Controllers\Admin\BookingController::class, 'showSelesaiForm'])->name('booking.selesai');
    Route::post('/booking/{id}/konfirmasi-selesai', [App\Http\Controllers\Admin\BookingController::class, 'konfirmasiSelesai'])->name('booking.konfirmasiSelesai');
    //batal acc booking
    Route::post('/booking/{id}/batal-acc', [App\Http\Controllers\Admin\BookingController::class, 'batalAcc'])->name('booking.batalAcc');
    // Kelola Layanan
    Route::resource('layanan', App\Http\Controllers\Admin\LayananController::class);
    // Kelola User
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    // Kelola  Groomer   
    Route::resource('groomer', App\Http\Controllers\Admin\GroomerController::class);
    // Kelola  Tim Groomer  
    Route::resource('tim-groomer', App\Http\Controllers\Admin\TimGroomerController::class);
    // Laporan
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
});

Route::prefix('admin/users/{user}/kucing')->name('admin.users.kucing.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('create', [App\Http\Controllers\Admin\KucingUserController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\KucingUserController::class, 'store'])->name('store');
    Route::get('{kucing}/edit', [App\Http\Controllers\Admin\KucingUserController::class, 'edit'])->name('edit');
    Route::put('{kucing}', [App\Http\Controllers\Admin\KucingUserController::class, 'update'])->name('update');
    Route::delete('{kucing}', [App\Http\Controllers\Admin\KucingUserController::class, 'destroy'])->name('destroy');
});