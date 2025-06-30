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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ClientController;

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                      ДРУГОЕ                       |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Формирование заявки                                  */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/

Route::post('/request-telegram-bot', [HomeController::class, 'requestTelegramBot']);
/* _____________________________________________________*/
/* Создание нового трека                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/create-track', [HomeController::class, 'createTrack']);
/* _____________________________________________________*/
/* Данные о баллах клиентов                             */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/clieints', [ClientController::class, 'getClients']);
Route::post('/clieint-points', [ClientController::class, 'getClientPoints']);
Route::post('/save-clieint-points', [ClientController::class, 'saveClientPoints'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                   АВТОРИЗАЦИЯ                     |*/
/* |___________________________________________________|*/
Route::post('/login', [LoginController::class, 'login']);

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                   ПОЛЬЗОВАТЕЛЬ                    |*/
/* |___________________________________________________|*/
Route::post('/check-user', [LoginController::class, 'checkUser'])->middleware('auth:sanctum');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/upload-file', [AdminController::class, 'uploadFile'])->middleware('auth:sanctum');
Route::get('/get-profile-info', [AdminController::class, 'getProfileInfo'])->middleware('auth:sanctum');

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                    СОЗДАТЕЛЬ                      |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Пользователи                                         */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-users-all', [CreatorController::class, 'getUsersAll'])->middleware(['auth:sanctum', 'creator']);
Route::post('/save-user', [CreatorController::class, 'saveUser'])->middleware(['auth:sanctum', 'creator']);
Route::post('/create-user', [CreatorController::class, 'createUser'])->middleware(['auth:sanctum', 'creator']);
Route::post('/delete-user', [CreatorController::class, 'deleteUser'])->middleware(['auth:sanctum', 'creator']);
Route::post('/set-user-password', [CreatorController::class, 'setUserPassword'])->middleware(['auth:sanctum', 'creator']);
Route::post('/set-user-status', [CreatorController::class, 'setUserStatus'])->middleware(['auth:sanctum', 'creator']);

/* _____________________________________________________*/
/* Статистика                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::post('/get-tracking-statistics-list', [CreatorController::class, 'getTrackingStatisticsList'])->middleware(['auth:sanctum', 'creator']);
Route::post('/get-tracking-statistics-range', [CreatorController::class, 'getTrackingStatisticsRange'])->middleware(['auth:sanctum', 'creator']);

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

/* _____________________________________________________*/
/* Контакты                                             */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-contacts-all', [ContactController::class, 'getContactsAll']);
Route::get('/get-contacts-clinics-all', [ContactController::class, 'getContactsClinicsAll']);

Route::post('/save-contacts-changes', [ContactController::class, 'saveContactsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Видео                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-videos-all', [VideoController::class, 'getVideosAll']);

Route::post('/add-video', [VideoController::class, 'addVideo'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-videos-changes', [VideoController::class, 'saveVideoChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
