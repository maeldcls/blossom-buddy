<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\UserPlantController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome');})->name('welcome');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/plant',[UserPlantController::class, 'addUserPlant']);
    Route::delete('/user/plant/{id}',[UserPlantController::class, 'deleteUserPlant']);
});

Route::get('/plant/update',[PlantController::class, 'fetchAndStoreAllPlants']);
Route::get('/plant',[PlantController::class, 'getAllPlants']);
Route::post('/plant',[PlantController::class, 'addPlant']);
Route::get('/plant/{name}',[PlantController::class, 'plantDetails']);
Route::delete('/plant/{id}',[PlantController::class, 'deletePlant']);

Route::get('/weather/{city}',[WeatherController::class, 'getWeather']);

