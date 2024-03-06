<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;



Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::post('/vehicles/store', [VehicleController::class, 'store'])->name('vehicles.store');
Route::get('/vehicles/edit/{uuid}', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::post('/vehicles/update/{uuid}', [VehicleController::class, 'update'])->name('vehicles.update');
Route::get('/vehicles/show/{uuid}', [VehicleController::class, 'show'])->name('vehicles.show');
Route::delete('/vehicles/destroy/{uuid}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
Route::get('/map', [VehicleController::class, 'map'])->name('vehicles.map');
Route::get('/send-sms', [VehicleController::class, 'sendSms']);
Route::get('/send-email', [VehicleController::class, 'sendEmail']);


Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/store-data', [UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{uuid}', [UserController::class, 'edit'])->name('users.edit');
Route::post('/users/update/{uuid}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/show/{uuid}', [UserController::class, 'show'])->name('users.show');
Route::delete('/users/destroy/{uuid}', [UserController::class, 'destroy'])->name('users.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/geofence', [VehicleController::class, 'showGeofenceForm'])->name('geofence');
    Route::post('/geofence/save', [VehicleController::class, 'saveGeofence'])->name('saveGeofence');
    Route::post('/send-geocodes-from-api', [VehicleController::class, 'sendGeocodesFromApi']);
    Route::get('/check-the-boundary', [VehicleController::class, 'checkBoundary']);
});