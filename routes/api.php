<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AnimalPhotoController;
use Illuminate\Support\Facades\Route;

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
Route::get('/animals', [AnimalController::class, 'index']); // Отримати список тварин
Route::post('/animals', [AnimalController::class, 'store']); // Додати нову тварину
Route::get('/animals/{id}', [AnimalController::class, 'show']); // Отримати конкретну тварину
Route::put('/animals/{id}', [AnimalController::class, 'update']); // Оновити дані тварини
Route::delete('/animals/{id}', [AnimalController::class, 'destroy']); // Видалити тварину

Route::post('/animals/{id}/photos', [AnimalPhotoController::class, 'store']); // Додати фото
Route::delete('/photos/{id}', [AnimalPhotoController::class, 'delete']); // Видалити фото

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
