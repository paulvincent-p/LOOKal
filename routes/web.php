<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerBusinessController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public: no login needed
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/emergency', [PublicController::class, 'emergency'])->name('emergency');
Route::get('/listings/{business}', [PublicController::class, 'show'])->name('listings.show');
Route::post('/listings/{business}/events', [PublicController::class, 'event'])->middleware('throttle:60,1')->name('listings.event');
Route::post('/listings/{business}/reviews', [PublicController::class, 'review'])->middleware('throttle:5,1')->name('listings.review');
Route::post('/listings/{business}/reports', [PublicController::class, 'report'])->middleware('throttle:5,1')->name('listings.report');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Business owner
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [OwnerBusinessController::class, 'index'])->name('dashboard');
    Route::get('/businesses/create', [OwnerBusinessController::class, 'create'])->name('businesses.create');
    Route::post('/businesses', [OwnerBusinessController::class, 'store'])->name('businesses.store');
    Route::get('/businesses/{business}/edit', [OwnerBusinessController::class, 'edit'])->name('businesses.edit');
    Route::put('/businesses/{business}', [OwnerBusinessController::class, 'update'])->name('businesses.update');
    Route::delete('/businesses/{business}', [OwnerBusinessController::class, 'destroy'])->name('businesses.destroy');
    Route::get('/businesses/{business}/qr', [OwnerBusinessController::class, 'qr'])->name('businesses.qr');
});

// Administrator
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/businesses', [Admin\BusinessController::class, 'index'])->name('businesses.index');
    Route::get('/businesses/export', [Admin\BusinessController::class, 'export'])->name('businesses.export');
    Route::get('/businesses/{business}/edit', [Admin\BusinessController::class, 'edit'])->name('businesses.edit');
    Route::put('/businesses/{business}', [Admin\BusinessController::class, 'update'])->name('businesses.update');
    Route::patch('/businesses/{business}/status', [Admin\BusinessController::class, 'updateStatus'])->name('businesses.status');
    Route::delete('/businesses/{business}', [Admin\BusinessController::class, 'destroy'])->name('businesses.destroy');

    Route::get('/reviews', [Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}', [Admin\ReviewController::class, 'toggle'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}', [Admin\ReportController::class, 'resolve'])->name('reports.resolve');

    Route::get('/lookups/{type}', [Admin\LookupController::class, 'index'])->where('type', 'categories|barangays')->name('lookups.index');
    Route::post('/lookups/{type}', [Admin\LookupController::class, 'store'])->where('type', 'categories|barangays')->name('lookups.store');
    Route::delete('/lookups/{type}/{id}', [Admin\LookupController::class, 'destroy'])->where('type', 'categories|barangays')->name('lookups.destroy');

    Route::get('/emergency-contacts', [Admin\EmergencyContactController::class, 'index'])->name('emergency.index');
    Route::post('/emergency-contacts', [Admin\EmergencyContactController::class, 'store'])->name('emergency.store');
    Route::delete('/emergency-contacts/{contact}', [Admin\EmergencyContactController::class, 'destroy'])->name('emergency.destroy');

    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}', [Admin\UserController::class, 'toggle'])->name('users.toggle');
});
