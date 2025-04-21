<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\DinerController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::resource('diners', DinerController::class);
Route::resource('tables', TableController::class);
Route::resource('reservations', ReservationController::class);
Route::get('search', [SearchController::class, 'search'])->name('reservations.search');
Route::get('tablesAvailabes', [SearchController::class, 'tablesAvailables'])->name('tables.availables');
