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
Route::post('/get-slides', [HomeController::class, 'getSlides']);
Route::post('/get-footer', [HomeController::class, 'getFooter']);

// Функционал администратора
Route::post('/save-footer', [AdminController::class, 'saveFooter'])->middleware('auth:sanctum');
Route::post('/clear-footer', [AdminController::class, 'clearFooter'])->middleware('auth:sanctum');
Route::post('/save-slides-changes', [AdminController::class, 'saveSlidesChanges'])->middleware('auth:sanctum');
Route::post('/upload-file', [AdminController::class, 'uploadFile'])->middleware('auth:sanctum');


