<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |              ПОЛУЧЕНИЕ ДАННЫХ ИЗ БД               |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Главная                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Слайдер                                           */
Route::post('/get-slides-all', [HomeController::class, 'getSlidesAll']);
/* 2. Слайдер (без статуса скрытия)                     */
Route::post('/get-slides-not-hide', [HomeController::class, 'getSlidesNotHide']);
/* 3. Футер                                             */
Route::post('/get-footer', [HomeController::class, 'getFooter']);
/* _____________________________________________________*/
/* Специалисты                                          */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Врачи                                             */
Route::post('/get-specialists', [HomeController::class, 'getSpecialists']);
Route::post('/get-specialist-profile', [HomeController::class, 'getSpecialistProfile']);
Route::post('/get-specialist-all', [HomeController::class, 'getSpecialistAll']);
Route::post('/get-specialist-sections', [HomeController::class, 'getSpecialistSections']);
Route::post('/get-specialists-short', [HomeController::class, 'getSpecialistsShort']);
/* 2. Специализации                                     */
Route::post('/get-specializations-all', [HomeController::class, 'getSpecializationsAll']);
/* 3. Клиники                                           */
Route::post('/get-clinics-all', [HomeController::class, 'getClinicsAll']);
/* 4. Образования                                       */
Route::post('/get-educations-all', [HomeController::class, 'getEducationsAll']);
/* 5. Прошлые работы                                    */
Route::post('/get-works-all', [HomeController::class, 'getWorksAll']);
/* 6. Сертификаты                                       */
Route::post('/get-certificates-all', [HomeController::class, 'getCertificatesAll']);
/* _____________________________________________________*/
/* О нас                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-abouts-all', [HomeController::class, 'getAboutsAll']);
/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                  АДМИНИСТРАТОР                    |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Общие                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/check-token', [LoginController::class, 'checkToken'])->middleware('auth:sanctum');
Route::post('/logout', [LoginController::class, 'logoutUser'])->middleware('auth:sanctum');
Route::post('/create', [LoginController::class, 'createUser']);
Route::post('/upload-file', [AdminController::class, 'uploadFile'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* Главная                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Слайдер                                           */
Route::post('/save-slides-changes', [AdminController::class, 'saveSlidesChanges'])->middleware('auth:sanctum');
/* 2. Футер                                           */
Route::post('/save-footer', [AdminController::class, 'saveFooter'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* Специалисты                                          */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Врачи                                             */
// Общее сохранение                                    
Route::post('/save-specialists-changes', [AdminController::class, 'saveSpecialistsChanges'])->middleware('auth:sanctum');
// Модульное сохранение                                    
Route::post('/save-specialist-modular', [AdminController::class, 'saveSpecialistModular'])->middleware('auth:sanctum');
// Добавление нового специалиста                                    
Route::post('/add-specialist', [AdminController::class, 'addSpecialist'])->middleware('auth:sanctum');
// Отдельные сохранения                                    
Route::post('/save-specialist-profile-changes', [AdminController::class, 'saveSpecialistProfileChanges'])->middleware('auth:sanctum');
Route::post('/save-specialist-specializations-changes', [AdminController::class, 'saveSpecialistSpecializationsChanges'])->middleware('auth:sanctum');
Route::post('/save-specialist-clinics-changes', [AdminController::class, 'saveSpecialistClinicsChanges'])->middleware('auth:sanctum');
Route::post('/save-specialist-certificates-changes', [AdminController::class, 'saveSpecialistCertificatesChanges'])->middleware('auth:sanctum');
Route::post('/save-specialist-educations-changes', [AdminController::class, 'saveSpecialistEducationsChanges'])->middleware('auth:sanctum');
Route::post('/save-specialist-works-changes', [AdminController::class, 'saveSpecialistWorksChanges'])->middleware('auth:sanctum');
/* 2. Специализации                                     */
Route::post('/save-specializations-changes', [AdminController::class, 'saveSpecializationsChanges'])->middleware('auth:sanctum');
/* 3. Клиники                                           */
Route::post('/save-clinics-changes', [AdminController::class, 'saveClinicsChanges'])->middleware('auth:sanctum');
/* 4. Образования                                       */
Route::post('/save-educations-changes', [AdminController::class, 'saveEducationsChanges'])->middleware('auth:sanctum');
/* 5. Прошлые работы                                    */
Route::post('/save-works-changes', [AdminController::class, 'saveWorkChanges'])->middleware('auth:sanctum');
/* 5. Прошлые работы                                    */
Route::post('/save-certificates-changes', [AdminController::class, 'saveCertificatesChanges'])->middleware('auth:sanctum');



