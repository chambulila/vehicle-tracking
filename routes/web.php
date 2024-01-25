<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\HomeController;



Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('vehicles.store');
Route::get('/vehicles/edit/{uuid}', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::get('/vehicles/update/{uuid}', [VehicleController::class, 'update'])->name('vehicles.update');
Route::get('/vehicles/show/{uuid}', [VehicleController::class, 'show'])->name('vehicles.show');
Route::delete('/vehicles/destroy/{uuid}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
Route::get('/map', [VehicleController::class, 'map'])->name('vehicles.map');