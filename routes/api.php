<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ShelterInfoController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\AnimalPhotoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Публічні маршрути
Route::get('/animals', [AnimalController::class, 'index']); // Отримати список тварин
Route::get('/animals/{id}', [AnimalController::class, 'show']); // Отримати конкретну тварину
Route::get('/shelter-info', [ShelterInfoController::class, 'index']); // Отримати інформацію про притулок

// Захищені маршрути (потребують автентифікації)
Route::middleware('auth:sanctum')->group(function () {
    // Маршрути для заявок на усиновлення
    Route::post('/adoption-requests', [AdoptionRequestController::class, 'store']);
});

// Адмін-маршрути
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Маршрути для управління тваринами
    Route::post('/animals', [AnimalController::class, 'store']);
    Route::put('/animals/{id}', [AnimalController::class, 'update']);
    Route::delete('/animals/{id}', [AnimalController::class, 'destroy']);
    
    // Маршрути для управління інформацією про притулок
    Route::apiResource('shelter-info', ShelterInfoController::class)->except(['index']);
    
    // Маршрути для управління заявками на усиновлення
    Route::get('/adoption-requests', [AdoptionRequestController::class, 'index']);
    Route::get('/adoption-requests/archived', [AdoptionRequestController::class, 'archived']);
    Route::put('/adoption-requests/{id}', [AdoptionRequestController::class, 'update']);
    Route::post('/adoption-requests/{id}/archive', [AdoptionRequestController::class, 'archive']);
    Route::post('/adoption-requests/{id}/restore', [AdoptionRequestController::class, 'restore']);
    Route::delete('/adoption-requests/{id}', [AdoptionRequestController::class, 'destroy']);
});

// Маршрути для фото тварин
Route::post('/animals/{id}/photos', [AnimalPhotoController::class, 'store']);
Route::delete('/photos/{id}', [AnimalPhotoController::class, 'delete']);
