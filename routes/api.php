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
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\TravelsController;

use App\Http\Middleware\TrackLoad;

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
Route::get('/get-tracking-statistics-list', [StatisticController::class, 'getTrackingStatisticsList'])->middleware(['auth:sanctum', 'admin-or-creator']);

Route::post('/get-tracking-statistics-range', [StatisticController::class, 'getTrackingStatisticsRange'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/get-tracking-statistics-diagram', [StatisticController::class, 'getTrackingStatisticsDiagram'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
/* |                     РАЗДЕЛЫ                       |*/
/* |___________________________________________________|*/
/* _____________________________________________________*/
/* Слайдер                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-slides-all', [SlidesController::class, 'getSlidesAll'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::get('/get-slides-not-hide', [SlidesController::class, 'getSlidesNotHide']);

Route::post('/save-slides-changes', [SlidesController::class, 'saveSlidesChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Футер                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-footer', [FooterController::class, 'getFooter'])->middleware(TrackLoad::class.':attendance');

Route::post('/save-footer', [FooterController::class, 'saveFooter'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Специалисты                                          */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-specialists', [SpecialistController::class, 'getSpecialists'])->middleware(TrackLoad::class . ':specialists,Cписок');
Route::post('/get-specialist-profile', [SpecialistController::class, 'getSpecialistProfile'])->middleware(TrackLoad::class . ':specialists,Профиль');
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
Route::get('/get-prices-choice', [PricesController::class, 'getPricesChoice'])->middleware(TrackLoad::class . ':prices,Общие');
Route::post('/get-prices-group', [PricesController::class, 'getPricesGruop'])->middleware(TrackLoad::class . ':prices,Группа');

Route::post('/get-prices-template', [PricesController::class, 'getPricesTemplate'])->middleware(TrackLoad::class . ':clinics');
Route::post('/get-prices-manual', [PricesController::class, 'getPricesManual'])->middleware(TrackLoad::class . ':clinics');

Route::post('/get-prices-complecte', [PricesController::class, 'getPricesComplecte'])->middleware(TrackLoad::class . ':prices,Список клиник');
Route::get('/get-prices-addresses-list', [PricesController::class, 'getPricesAddressesList']);
Route::get('/get-prices-categories-list', [PricesController::class, 'getPricesCategoriesList']);
Route::get('/download-prices-archive', [PricesController::class, 'downloadPricesArchive']);

Route::get('/get-prices-files-all', [PricesController::class, 'getPricesFilesAll'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-prices-changes', [PricesController::class, 'savePricesChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/make-prices-files', [PricesController::class, 'makePricesFiles'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Путевки                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-travels', [TravelsController::class, 'getTravels'])->middleware(TrackLoad::class . ':travels,Путевки');
Route::get('/get-travels-all', [TravelsController::class, 'getTravelsAll'])->middleware(['auth:sanctum', 'admin-or-creator']);

Route::post('/get-travels-once', [TravelsController::class, 'getTravelsOnce'])->middleware(TrackLoad::class . ':travels,Путевки');
Route::post('/save-travels-changes', [TravelsController::class, 'saveTravelsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Расписание                                           */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-shedules-all', [SheduleController::class, 'getShedulesAll'])->middleware(TrackLoad::class . ':shedule,Расписание');

Route::post('/save-shedules-all/{type}', [SheduleController::class, 'saveShedulesAll'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* О нас                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-abouts-all', [AboutController::class, 'getAboutsAll'])->middleware(TrackLoad::class . ':about,О нас');
Route::get('/get-info-files-all', [AboutController::class, 'getInfoFilesAll']);

Route::post('/save-abouts-changes', [AboutController::class, 'saveAboutsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-info-files-changes', [AboutController::class, 'saveInfoFilesChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Новости                                              */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-news-all', [NewsController::class, 'getNewsAll']);
Route::post('/get-news-short', [NewsController::class, 'getNewsShort'])->middleware(TrackLoad::class . ':news,Раздел');
Route::post('/get-news-once-without', [NewsController::class, 'getNewsOnceWithout'])->middleware(TrackLoad::class . ':news,Пост');
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
Route::get('/get-contacts-clinics-all', [ContactController::class, 'getContactsClinicsAll'])->middleware(TrackLoad::class . ':contacts,Список');

Route::post('/save-contacts-changes', [ContactController::class, 'saveContactsChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);

/* _____________________________________________________*/
/* Видео                                                */
/* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
Route::get('/get-videos-all', [VideoController::class, 'getVideosAll'])->middleware(TrackLoad::class . ':video,Видео');

Route::post('/add-video', [VideoController::class, 'addVideo'])->middleware(['auth:sanctum', 'admin-or-creator']);
Route::post('/save-videos-changes', [VideoController::class, 'saveVideoChanges'])->middleware(['auth:sanctum', 'admin-or-creator']);
