<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/edit/{uuid}', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicles.edit');
Route::get('/vehicles/update/{uuid}', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicles.update');
Route::get('/vehicles/show/{uuid}', [App\Http\Controllers\VehicleController::class, 'show'])->name('vehicles.show');
Route::get('/vehicles/destroy/{uuid}', [App\Http\Controllers\VehicleController::class, 'destroy'])->name('vehicles.destroy');
