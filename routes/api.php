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
/* 1. Отправка заявки                                   */
Route::post('/request-telegram-bot', [HomeController::class, 'requestTelegramBot']);
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
/* _____________________________________________________*/
/* Контакты                                             */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-contacts-all', [HomeController::class, 'getContactsAll']);
Route::post('/get-contacts-clinics-all', [HomeController::class, 'getContactsClinicsAll']);
/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                  АДМИНИСТРАТОР                    |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Общие                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/check-user', [LoginController::class, 'checkUser'])->middleware('auth:sanctum');
Route::post('/logout', [LoginController::class, 'logoutUser'])->middleware('auth:sanctum');
Route::post('/create', [LoginController::class, 'createUser']);
Route::post('/upload-file', [AdminController::class, 'uploadFile'])->middleware('auth:sanctum');
Route::post('/get-profile-info', [AdminController::class, 'getProfileInfo'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* Главная                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Слайдер                                           */
Route::post('/save-slides-changes', [AdminController::class, 'saveSlidesChanges'])->middleware('auth:sanctum');
/* 2. Футер                                           */
Route::post('/save-footer', [AdminController::class, 'saveFooter'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* О нас                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Информационные блоки                              */
Route::post('/save-abouts-changes', [AdminController::class, 'saveAboutsChanges'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* Контакты                                             */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Информационные блоки                              */
Route::post('/save-contacts-changes', [AdminController::class, 'saveContactsChanges'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* Специалисты                                          */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
/* 1. Специалисты                                       */
Route::post('/save-specialists-changes', [AdminController::class, 'saveSpecialistsChanges'])->middleware('auth:sanctum');
Route::post('/save-specialist-modular', [AdminController::class, 'saveSpecialistModular'])->middleware('auth:sanctum');
Route::post('/add-specialist', [AdminController::class, 'addSpecialist'])->middleware('auth:sanctum');
/* 2. Специализации                                     */
Route::post('/save-specializations-changes', [AdminController::class, 'saveSpecializationsChanges'])->middleware('auth:sanctum');
/* 3. Клиники                                           */
Route::post('/save-clinics-changes', [AdminController::class, 'saveClinicsChanges'])->middleware('auth:sanctum');
/* 4. Образования                                       */
Route::post('/save-educations-changes', [AdminController::class, 'saveEducationsChanges'])->middleware('auth:sanctum');
/* 5. Прошлые работы                                    */
Route::post('/save-works-changes', [AdminController::class, 'saveWorkChanges'])->middleware('auth:sanctum');
/* 6. Прошлые работы                                    */
Route::post('/save-certificates-changes', [AdminController::class, 'saveCertificatesChanges'])->middleware('auth:sanctum');
/* _____________________________________________________*/
/* Расписание                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/save-shedules-all', [AdminController::class, 'saveShedulesAll']);
Route::post('/get-shedules-all', [HomeController::class, 'getShedulesAll']);
/* _____________________________________________________*/
/* Цены                                                 */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-prices-files-all', [AdminController::class, 'getPricesFilesAll'])->middleware('auth:sanctum');
Route::post('/save-prices-changes', [AdminController::class, 'savePricesChanges'])->middleware('auth:sanctum');
/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                    СОЗДАТЕЛЬ                      |*/
/* |___________________________________________________|*/
/* 1. Права                                             */
Route::post('/chek-user-rigths', [CreatorController::class, 'chekUserRigths'])->middleware('auth:sanctum');
Route::post('/is-creator', [CreatorController::class, 'isCreator'])->middleware('auth:sanctum');
/* 2. Пользователи                                      */
Route::post('/get-users-all', [CreatorController::class, 'getUsersAll'])->middleware('auth:sanctum');
Route::post('/save-user', [CreatorController::class, 'saveUser'])->middleware('auth:sanctum');
Route::post('/create-user', [CreatorController::class, 'createUser'])->middleware('auth:sanctum');
Route::post('/delete-user', [CreatorController::class, 'deleteUser'])->middleware('auth:sanctum');
Route::post('/set-user-password', [CreatorController::class, 'setUserPassword'])->middleware('auth:sanctum');
Route::post('/set-user-status', [CreatorController::class, 'setUserStatus'])->middleware('auth:sanctum');



