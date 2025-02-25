<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminReportController;



// Halaman Welcome
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Group route dengan middleware 'auth'
Route::middleware(['auth'])->group(function () {
    
    // Dashboard umum
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Manajemen Tiket
    Route::get('tickets/data', [TicketController::class, 'getData'])->name('tickets.data');
    
    Route::resource('tickets', TicketController::class);

    // Manajemen Pengguna
    Route::resource('users', UserController::class);

    // Manajemen Booking
    Route::get('/bookings/data', [BookingController::class, 'getData'])->name('bookings.data');
    Route::resource('bookings', BookingController::class);

    // Route berdasarkan role (Super Admin, Admin, User)
    Route::middleware(['check-role:super-admin'])->prefix('super-admin')->group(function () {
        Route::get('/tickets', [TicketController::class, 'super'])->name('super-admin-tickets');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/tickets/trashed', [TicketController::class, 'trashed'])->name('tickets.trashed');
        Route::put('/tickets/{id}/restore', [TicketController::class, 'restore'])->name('tickets.restore');
        Route::delete('/tickets/{id}/force-delete', [TicketController::class, 'forceDelete'])->name('tickets.forceDelete');

    });

    Route::middleware(['check-role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [TicketController::class, 'admin'])->name('admin-dashboard');
        Route::get('/bookings/data', [BookingController::class, 'pushData'])->name('bookings.pushData'); 
        Route::put('/admin/bookings/{id}/confirm', [BookingController::class, 'confirmPayment'])->name('admin.bookings.confirm');
        Route::delete('/admin/bookings/hapus/{booking}', [BookingController::class, 'hapus'])->name('bookings.hapus');
        Route::delete('/admin/bookings/delete/{booking}', [BookingController::class, 'delete'])->name('admin.bookings.delete');
        Route::get('/bookings/trashed', [BookingController::class, 'trashed'])->name('bookings.trashed');
        Route::put('/bookings/{id}/restore', [BookingController::class, 'restore'])->name('bookings.restore');
        Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports');
        Route::get('/admin/reports/data', [AdminReportController::class, 'getData'])->name('reports.data');
        Route::delete('/bookings/{id}/force-delete', [BookingController::class, 'forceDelete'])->name('bookings.forceDelete');
    });

    Route::middleware(['check-role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [TicketController::class, 'user'])->name('user-dashboard');
        Route::get('/tickets', [TicketController::class, 'index'])->name('user.index');
        Route::get('/tickets/order/{id}', [BookingController::class, 'order'])->name('tickets.order');
        Route::post('/tickets/order/{id}', [BookingController::class, 'jual'])->name('tickets.order.store');
        Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history'); 
        Route::get('/history/data', [BookingController::class, 'historyData'])->name('booking.history.data');
        Route::get('/bookings/{id}/print', [BookingController::class, 'print'])->name('bookings.print');
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::put('/user/bookings/{id}/cancel', [BookingController::class, 'cancelBooking'])->name('user.bookings.cancel');
        Route::get('/helodata', [BookingController::class, 'helodata'])->name('helodata');
    });

});
