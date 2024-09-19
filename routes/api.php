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
/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                  ОБЩИЕ ФУНКЦИИ                    |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* 1. Главная                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-slides-all', [HomeController::class, 'getSlidesAll']);
Route::post('/get-slides-not-hide', [HomeController::class, 'getSlidesNotHide']);
Route::post('/get-footer', [HomeController::class, 'getFooter']);
/* _____________________________________________________*/
/* 2. Специалисты                                       */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-specialists', [HomeController::class, 'getSpecialists']);
Route::post('/get-specialist-profile', [HomeController::class, 'getSpecialistProfile']);
Route::post('/get-specialist-all', [HomeController::class, 'getSpecialistAll']);
Route::post('/get-specialists-short', [HomeController::class, 'getSpecialistsShort']);
/* _____________________________________________________*/
/* 3. Специализации                                     */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-specializations-all', [HomeController::class, 'getSpecializationsAll']);
Route::post('/get-clinics-all', [HomeController::class, 'getClinicsAll']);
Route::post('/get-educations-all', [HomeController::class, 'getEducationsAll']);
/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                  АДМИНИСТРАТОР                    |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Общие                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/upload-file', [AdminController::class, 'uploadFile'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* 1. Главная                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/save-footer', [AdminController::class, 'saveFooter'])->middleware('auth:sanctum');
Route::post('/save-slides-changes', [AdminController::class, 'saveSlidesChanges'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* 2. Специалисты                                       */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/save-specialists-hides', [AdminController::class, 'saveSpecialistsHides'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* 3. Специализации                                     */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/save-specializations-changes', [AdminController::class, 'saveSpecializationsChanges'])->middleware('auth:sanctum');




