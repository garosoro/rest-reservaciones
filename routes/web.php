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

Route::resource('diners', controller: DinerController::class);


//Tables Routes
Route::get('tables', [TableController::class, 'index'])->name('tables.index');
Route::get('tables/create', [TableController::class, 'create'])->name('tables.create');
Route::post('tables', [TableController::class, 'store'])->name('tables.store');
Route::get('tables/{table}', [TableController::class, 'show'])->name('tables.show');
Route::get('tables/{table}/edit', [TableController::class, 'edit'])->name('tables.edit');
Route::put('tables/{table}', [TableController::class, 'update'])->name('tables.update');
Route::delete('tables/{table}', [TableController::class, 'destroy'])->name('tables.destroy');
Route::get('tables/available', [TableController::class, 'available'])->name('tablesAva');

//Reservations Routes
Route::get('reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::get('reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
Route::get('reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
Route::put('reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
Route::get('reservation/tables/availables', [ReservationController::class, 'tablesAvailables'])->name('reservations.tables.availables');
Route::get('reservation/diners/search', [ReservationController::class, 'search'])->name('reservations.diners.search');
Route::get('reservation/actives', [ReservationController::class, 'activeReservations'])->name('reservations.active');

Route::get('search/tables/available', [SearchController::class, 'available'])->name('search.available');
