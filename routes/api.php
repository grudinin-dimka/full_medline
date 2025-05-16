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
/* Главная */
Route::get('/get-slides-all', [HomeController::class, 'getSlidesAll']);
Route::get('/get-slides-not-hide', [HomeController::class, 'getSlidesNotHide']);
Route::get('/get-footer', [HomeController::class, 'getFooter']);

/* О нас */
Route::get('/get-abouts-all', [HomeController::class, 'getAboutsAll']);

/* Специалисты */
Route::get('/get-specialists', [HomeController::class, 'getSpecialists']);
Route::post('/get-specialist-profile', [HomeController::class, 'getSpecialistProfile']);
Route::post('/get-specialist-all', [HomeController::class, 'getSpecialistAll']);
Route::post('/get-specialist-sections', [HomeController::class, 'getSpecialistSections']);
Route::get('/get-specialists-short', [HomeController::class, 'getSpecialistsShort']);

Route::get('/get-specializations-all', [HomeController::class, 'getSpecializationsAll']);
Route::get('/get-clinics-all', [HomeController::class, 'getClinicsAll']);

/* Цены */
Route::get('/download-prices-archive', [AdminController::class, 'downloadPricesArchive']);

Route::get('/get-prices-choice', [HomeController::class, 'getPricesChoice']);
Route::post('/get-prices-group', [HomeController::class, 'getPricesGruop']);
Route::post('/get-prices-template', [HomeController::class, 'getPricesTemplate']);
Route::get('/get-prices-all', [HomeController::class, 'getPricesAll']);

/* Контакты */
Route::get('/get-contacts-all', [HomeController::class, 'getContactsAll']);
Route::get('/get-contacts-clinics-all', [HomeController::class, 'getContactsClinicsAll']);

/* Расписание */
Route::get('/get-shedules-all', [HomeController::class, 'getShedulesAll']);

/* Новости */
Route::get('/get-news-all', [HomeController::class, 'getNewsAll']);
Route::post('/get-news-short', [HomeController::class, 'getNewsShort']);
Route::post('/get-news-once-without', [HomeController::class, 'getNewsOnceWithout']);
Route::post('/get-news-more', [HomeController::class, 'getNewsMore']);

/* Видео */
Route::get('/get-videos-all', [HomeController::class, 'getVideosAll']);

/* Запись нового события */
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
   Route::post('/make-prices-files', [AdminController::class, 'makePricesFiles']);

   // Новости 
   Route::post('/get-news-once', [HomeController::class, 'getNewsOnce']);

   Route::post('/add-news', [AdminController::class, 'addNews']);
   Route::post('/save-news-changes-all', [AdminController::class, 'saveNewsChangesAll']);
   Route::post('/save-news-changes-once', [AdminController::class, 'saveNewsChangesOnce']);
   Route::post('/publish-news-once', [AdminController::class, 'publishNewsOnce']);

   // Новости 
   Route::post('/add-video', [AdminController::class, 'addVideo']);
   Route::post('/save-videos-changes', [AdminController::class, 'saveVideoChanges']);
});

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                    СОЗДАТЕЛЬ                      |*/
/* |___________________________________________________|*/
Route::middleware(['auth:sanctum', 'creator'])->group(function () {
   // Пользователи
   Route::get('/get-users-all', [CreatorController::class, 'getUsersAll']);
   Route::post('/save-user', [CreatorController::class, 'saveUser'])->middleware('auth:sanctum');
   Route::post('/create-user', [CreatorController::class, 'createUser'])->middleware('auth:sanctum');
   Route::post('/delete-user', [CreatorController::class, 'deleteUser'])->middleware('auth:sanctum');
   Route::post('/set-user-password', [CreatorController::class, 'setUserPassword'])->middleware('auth:sanctum');
   Route::post('/set-user-status', [CreatorController::class, 'setUserStatus'])->middleware('auth:sanctum');
   
   // Статистика
   Route::post('/get-tracking-statistics-list', [CreatorController::class, 'getTrackingStatisticsList']);
   Route::post('/get-tracking-statistics-range', [CreatorController::class, 'getTrackingStatisticsRange']);
});


