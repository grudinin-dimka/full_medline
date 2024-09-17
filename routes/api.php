<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

// Работа с пользователями
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/check-token', [LoginController::class, 'checkToken'])->middleware('auth:sanctum');
Route::post('/logout', [LoginController::class, 'logoutUser'])->middleware('auth:sanctum');
Route::post('/create', [LoginController::class, 'createUser']);

// Общий функционал
Route::post('/get-slides-all', [HomeController::class, 'getSlidesAll']);
Route::post('/get-slides-not-hide', [HomeController::class, 'getSlidesNotHide']);
Route::post('/get-footer', [HomeController::class, 'getFooter']);
Route::post('/get-specialists', [HomeController::class, 'getSpecialists']);
Route::post('/get-specialist-profile', [HomeController::class, 'getSpecialistProfile']);
Route::post('/get-specialist-all', [HomeController::class, 'getSpecialistAll']);
Route::post('/get-specialists-short', [HomeController::class, 'getSpecialistsShort']);

// Функционал администратора
Route::post('/save-footer', [AdminController::class, 'saveFooter'])->middleware('auth:sanctum');
Route::post('/clear-footer', [AdminController::class, 'clearFooter'])->middleware('auth:sanctum');
Route::post('/save-slides-changes', [AdminController::class, 'saveSlidesChanges'])->middleware('auth:sanctum');
Route::post('/save-specialists-hides', [AdminController::class, 'saveSpecialistsHides'])->middleware('auth:sanctum');
Route::post('/upload-file', [AdminController::class, 'uploadFile'])->middleware('auth:sanctum');


