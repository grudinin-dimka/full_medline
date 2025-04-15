<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CreatorController;

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                   БОТ ТЕЛЕГРАММ                   |*/
/* |___________________________________________________|*/
Route::post('/request-telegram-bot', [HomeController::class, 'requestTelegramBot']);

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                   АВТОРИЗАЦИЯ                     |*/
/* |___________________________________________________|*/
Route::post('/login', [LoginController::class, 'login']);

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |              ПОЛУЧЕНИЕ ДАННЫХ ИЗ БД               |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Главная                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Слайдер                                           */
Route::get('/get-slides-all', [HomeController::class, 'getSlidesAll']);
/* 2. Слайдер (без статуса скрытия)                     */
Route::get('/get-slides-not-hide', [HomeController::class, 'getSlidesNotHide']);
/* 3. Футер                                             */
Route::get('/get-footer', [HomeController::class, 'getFooter']);
/* _____________________________________________________*/
/* О нас                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-abouts-all', [HomeController::class, 'getAboutsAll']);
/* _____________________________________________________*/
/* Специалисты                                          */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Врачи                                             */
Route::get('/get-specialists', [HomeController::class, 'getSpecialists']);
Route::post('/get-specialist-profile', [HomeController::class, 'getSpecialistProfile']);
Route::post('/get-specialist-all', [HomeController::class, 'getSpecialistAll']);
Route::post('/get-specialist-sections', [HomeController::class, 'getSpecialistSections']);
Route::get('/get-specialists-short', [HomeController::class, 'getSpecialistsShort']);
/* 2. Специализации                                     */
Route::get('/get-specializations-all', [HomeController::class, 'getSpecializationsAll']);
/* 3. Клиники                                           */
Route::get('/get-clinics-all', [HomeController::class, 'getClinicsAll']);
/* _____________________________________________________*/
/* Цены                                                 */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-prices-choice', [HomeController::class, 'getPricesChoice']);
Route::post('/get-prices-group', [HomeController::class, 'getPricesGruop']);
Route::post('/get-prices-template', [HomeController::class, 'getPricesTemplate']);
Route::get('/get-prices-all', [HomeController::class, 'getPricesAll']);
/* _____________________________________________________*/
/* Контакты                                             */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-contacts-all', [HomeController::class, 'getContactsAll']);
Route::get('/get-contacts-clinics-all', [HomeController::class, 'getContactsClinicsAll']);
/* _____________________________________________________*/
/* Расписание                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-shedules-all', [HomeController::class, 'getShedulesAll']);
/* _____________________________________________________*/
/* Запись нового события                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/create-track', [HomeController::class, 'createTrack']);

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                   ПОЛЬЗОВАТЕЛЬ                    |*/
/* |___________________________________________________|*/
Route::middleware('auth:sanctum')->group(function () {
   // Общие
   Route::post('/check-user', [LoginController::class, 'checkUser']);
   Route::post('/logout', [LoginController::class, 'logout']);
   Route::post('/upload-file', [AdminController::class, 'uploadFile']);
   Route::get('/get-profile-info', [AdminController::class, 'getProfileInfo']);   
});

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                  АДМИНИСТРАТОР                    |*/
/* |___________________________________________________|*/
Route::middleware(['auth:sanctum', 'admin-or-creator'])->group(function () {
   // Главная
   Route::post('/save-slides-changes', [AdminController::class, 'saveSlidesChanges']);
   Route::post('/save-footer', [AdminController::class, 'saveFooter']);

   // О нас
   Route::post('/save-abouts-changes', [AdminController::class, 'saveAboutsChanges'])->middleware('auth:sanctum');

   // Контакты
   Route::post('/save-contacts-changes', [AdminController::class, 'saveContactsChanges'])->middleware('auth:sanctum');

   // Специалисты
   Route::post('/save-specialists-changes', [AdminController::class, 'saveSpecialistsChanges'])->middleware('auth:sanctum');
   Route::post('/save-specialist-modular', [AdminController::class, 'saveSpecialistModular'])->middleware('auth:sanctum');
   Route::post('/add-specialist', [AdminController::class, 'addSpecialist'])->middleware('auth:sanctum');

   Route::post('/save-specializations-changes', [AdminController::class, 'saveSpecializationsChanges'])->middleware('auth:sanctum');

   Route::post('/save-clinics-changes', [AdminController::class, 'saveClinicsChanges'])->middleware('auth:sanctum');

   // Расписание 
   Route::post('/save-shedules-all', [AdminController::class, 'saveShedulesAll']);

   // Цены 
   Route::get('/get-prices-files-all', [AdminController::class, 'getPricesFilesAll']);
   Route::post('/save-prices-changes', [AdminController::class, 'savePricesChanges']);
});

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                    СОЗДАТЕЛЬ                      |*/
/* |___________________________________________________|*/
Route::middleware(['auth:sanctum', 'creator'])->group(function () {
   Route::get('/get-users-all', [CreatorController::class, 'getUsersAll']);
   Route::get('/get-tracking-statistics', [CreatorController::class, 'getTrackingStatistics']);

   Route::post('/save-user', [CreatorController::class, 'saveUser'])->middleware('auth:sanctum');
   Route::post('/create-user', [CreatorController::class, 'createUser'])->middleware('auth:sanctum');
   Route::post('/delete-user', [CreatorController::class, 'deleteUser'])->middleware('auth:sanctum');
   Route::post('/set-user-password', [CreatorController::class, 'setUserPassword'])->middleware('auth:sanctum');
   Route::post('/set-user-status', [CreatorController::class, 'setUserStatus'])->middleware('auth:sanctum');
});


