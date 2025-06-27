<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CreatorController;

use App\Http\Controllers\SlidesController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\SheduleController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\NewsController;

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
/* Контакты */
Route::get('/get-contacts-all', [HomeController::class, 'getContactsAll']);
Route::get('/get-contacts-clinics-all', [HomeController::class, 'getContactsClinicsAll']);

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
   // Контакты
   Route::post('/save-contacts-changes', [AdminController::class, 'saveContactsChanges'])->middleware('auth:sanctum');

   // Цены 
   Route::get('/get-prices-files-all', [AdminController::class, 'getPricesFilesAll']);

   Route::post('/save-prices-changes', [AdminController::class, 'savePricesChanges']);
   Route::post('/make-prices-files', [AdminController::class, 'makePricesFiles']);

   // Видео 
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

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                     РАЗДЕЛЫ                       |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Слайдер                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-slides-all', [SlidesController::class, 'getSlidesAll']);
Route::get('/get-slides-not-hide', [SlidesController::class, 'getSlidesNotHide']);

Route::post('/save-slides-changes', [SlidesController::class, 'saveSlidesChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Футер                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-footer', [FooterController::class, 'getFooter']);

Route::post('/save-footer', [FooterController::class, 'saveFooter'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Специалисты                                          */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-specialists', [SpecialistController::class, 'getSpecialists']);
Route::post('/get-specialist-profile', [SpecialistController::class, 'getSpecialistProfile']);
Route::post('/get-specialist-all', [SpecialistController::class, 'getSpecialistAll']);
Route::post('/get-specialist-sections', [SpecialistController::class, 'getSpecialistSections']);
Route::get('/get-specialists-short', [SpecialistController::class, 'getSpecialistsShort']);
Route::get('/get-specializations-all', [SpecialistController::class, 'getSpecializationsAll']);
Route::get('/get-clinics-all', [SpecialistController::class, 'getClinicsAll']);

Route::post('/save-specialists-changes', [SpecialistController::class, 'saveSpecialistsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-specialist-modular', [SpecialistController::class, 'saveSpecialistModular'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/add-specialist', [SpecialistController::class, 'addSpecialist'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-specializations-changes', [SpecialistController::class, 'saveSpecializationsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-clinics-changes', [SpecialistController::class, 'saveClinicsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/make-specialists-xml', [SpecialistController::class, 'makeSpecialistsXML'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Цены                                                 */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-prices-choice', [PricesController::class, 'getPricesChoice']);
Route::post('/get-prices-group', [PricesController::class, 'getPricesGruop']);
Route::post('/get-prices-template', [PricesController::class, 'getPricesTemplate']);
Route::post('/get-prices-manual', [PricesController::class, 'getPricesManual']);
Route::post('/get-prices-complecte', [PricesController::class, 'getPricesComplecte']);
Route::get('/get-prices-addresses-list', [PricesController::class, 'getPricesAddressesList']);
Route::get('/get-prices-categories-list', [PricesController::class, 'getPricesCategoriesList']);
Route::get('/download-prices-archive', [PricesController::class, 'downloadPricesArchive']);

Route::get('/get-prices-files-all', [PricesController::class, 'getPricesFilesAll'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-prices-changes', [PricesController::class, 'savePricesChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/make-prices-files', [PricesController::class, 'makePricesFiles'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Расписание                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-shedules-all', [SheduleController::class, 'getShedulesAll']);

Route::post('/save-shedules-all/{type}', [SheduleController::class, 'saveShedulesAll'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* О нас                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-abouts-all', [AboutController::class, 'getAboutsAll']);
Route::get('/get-info-files-all', [AboutController::class, 'getInfoFilesAll']);

Route::post('/save-abouts-changes', [AboutController::class, 'saveAboutsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-info-files-changes', [AboutController::class, 'saveInfoFilesChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Новости                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-news-all', [NewsController::class, 'getNewsAll']);
Route::post('/get-news-short', [NewsController::class, 'getNewsShort']);
Route::post('/get-news-once-without', [NewsController::class, 'getNewsOnceWithout']);
Route::post('/get-news-more', [NewsController::class, 'getNewsMore']);

Route::post('/get-news-once', [NewsController::class, 'getNewsOnce'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/add-news', [NewsController::class, 'addNews'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-news-changes-all', [NewsController::class, 'saveNewsChangesAll'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-news-changes-once', [NewsController::class, 'saveNewsChangesOnce'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/publish-news-once', [NewsController::class, 'publishNewsOnce'])->middleware(['auth:sanctum', 'admin-or-creator']);
