<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

// Для загрузки файлов
use Illuminate\Support\Facades\Storage;
Use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\File;

use App\Models\User;
use App\Models\Slide;
use App\Models\Footer;
use App\Models\Specialist;
use App\Models\Specialization;
use App\Models\Clinic;

class AdminController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ОБЩИЕ                         |*/
   /* |___________________________________________________|*/
   /* Загрузка файла на сервер */ 
   public function uploadFile(Request $request) {
		/* Проверка на наличие переменной image с файлом в запросе */  
		if($request->hasFile('image')) {
         $validated = validator($request->all(), [
            'image' => [
               'required',
               File::types('png')->max(10 * 1024),
            ],
         ]);
         if ($validated->fails()) return false;

         switch ($request->type) {
         case 'slide':
            $path = $request->file('image')->store(
               'public/slides'
            );
            break;
         case 'specialist':
            $path = $request->file('image')->store(
               'public/specialists'
            );
            break;
         }
      };
      
      return Storage::url($path);
   } 
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    СЛАЙДЕР                        |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Сохранение, удаление                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   // Сохранение содержимого всех слайдов
      public function saveSlidesChanges(Request $request) {
      /* Удаление слайдов, подлежащих удалению */
      foreach ($request->slides as $key => $value) {
         if ($value['delete'] === true) {
         $slide = Slide::find($value['id']);
         $slide->delete();
         };
      };

      $arrayID = [];
      /* Добавление новых слайдов */
      foreach ($request->slides as $key => $value) {
         if ($value['create'] === true){
            $slideCreate = Slide::create([
               'name' => $value['name'],
               'link' => $value['link'],
               'filename' => $value['filename'],
               'hide' => $value['hide'],
               'order' => $value['order'],
            ]);

            // Запись нового объекта в массив
            $arrayID[] = (object) [
               /* Прошлый id */
               'old' => $value['id'], 
               /* Новый id */
               'new' => $slideCreate->id
            ];
         };
      };

      /* Обновление существующих слайдов */
      foreach ($request->slides as $key => $value) {
         if ($value['delete'] !== true && $value['create'] !== true) {
            $slide = Slide::find($value['id']);
            $slideUpdate = $slide->update([
               'name' => $value['name'],
               'link' => $value['link'],
               'filename' => $value['filename'],
               'hide' => $value['hide'],
               'order' => $value['order'],
            ]);  
         };
      };

      /* Сортировка слайдов по порядку от наименьшего до наибольшого */
      $slides = Slide::all()->SortBy('order');

      /* Обновление порядковых номеров */
      $count = 0;
      foreach ($slides as $slideKey => $slideValue) {
         $count++;
         $slideValue->order = $count;
         $slideValue->save();
      };

      /* Получение всех файлов */
      $filesSlides = Storage::files('public/slides');

      foreach ($filesSlides as $fileKey => $fileValue) {
         $useFile = false;
         // Проверка на использование файла
         foreach ($slides as $slideKey => $slideValue) {
         // Обрезание значения $fileValue до названия файла
         $str = str_replace('public/slides/', '', $fileValue);
         // Проверка значения названия файла на совпадение
         if ($slideValue->filename == $str) {
            $useFile = true;
         };
         };

         // Удаление файла, если не используется
         if (!$useFile) {
         Storage::delete($fileValue);
         };
      };

      return $arrayID;
   } 
  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |                  СПЕЦИАЛИСТЫ                      |*/
  /* |___________________________________________________|*/
  /* _____________________________________________________*/
  /* 1. Сохранение, удаление                              */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Сохранение специалистов 
   public function saveSpecialistsChanges(Request $request) {
      /* Удаление специалистов, подлежащих удалению */
      foreach ($request->specialists as $key => $value) {
         if ($value['delete'] === true) {
            var_dump('id: ' . $value['id']);
            $specialist = Specialist::find($value['id']);
            $specialist->delete();
         };
      };
      
      /* Добавление новых специалистов */
      foreach ($request->specialists as $key => $value) {
         if ($value["create"] === true){
            $specialistCreate = Specialist::create([
               "name" => $value['name'],
               "specialization" => $value['specialization'],
               "startWorkAge" => $value['startWorkAge'],
               "specializationAdvanced" => $value['specializationAdvanced'],
               "education" => $value['education'],
               "link" => $value['link'],
               "hide" => $value['hide'],
               "filename" => $value['filename']   
            ]);
         };
      };

      return true;
   }
  // Сохранение специалистов 
   public function saveSpecialistsHides(Request $request) {
      foreach ($request->specialists as $key => $value) {
         $specialist = Specialist::find($value['id']);
         $specialist->hide = $value['hide'];
         $specialist->save();
      }

      return true;
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 СПЕЦИАЛИЗАЦИИ                     |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Сохранение, удаление                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function saveSpecializationsChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->specializations as $key => $value) {
         if ($value["delete"] === true){
            $specialization = Specialization::find($value['id']);
            $specialization->delete();
         }         
      }

      $arrayID = [];
      // Добавление новых
      foreach ($request->specializations as $key => $value) {
         if ($value['create'] === true) {
            $specializationCreate = Specialization::create([
               "name" => $value['name'],
            ]);

            // Запись нового объекта в массив
            $arrayID[] = (object) [
               /* Прошлый id */
               'old' => $value['id'], 
               /* Новый id */
               'new' => $specializationCreate->id
            ];            
         };       
      }

      // Обновление данных 
      foreach ($request->specializations as $key => $value) {
         if ($value["delete"] !== true && $value['create'] !== true){
            $specialization = Specialization::find($value['id']);
            $specializationUpdate = $specialization->update([
               'name' => $value['name'],
            ]);     
         }         
      }
      return $arrayID;
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    КЛИНИКИ                        |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Сохранение, удаление                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function saveClinicsChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->clinics as $key => $value) {
         if ($value["delete"] === true){
            $clinic = Clinic::find($value['id']);
            $clinic->delete();
         }         
      }

      $arrayID = [];
      // Добавление новых
      foreach ($request->clinics as $key => $value) {
         if ($value["create"] === true) {
            $clinicToCreate = Clinic::create([
               "name" => $value['name'],
               "city" => $value['city'],
               "street" => $value['street'],
               "home" => $value['home'],
               "index" => $value['index'],
               "geoWidth" => $value['geoWidth'],
               "geoLongitude" => $value['geoLongitude'],
            ]);

            // Запись нового объекта в массив
            $arrayID[] = (object) [
               /* Прошлый id */
               'old' => $value['id'], 
               /* Новый id */
               'new' => $clinicToCreate->id
            ];            
         };       
      }
      return $arrayID;
   }

  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |                     ФУТЕР                         |*/
  /* |___________________________________________________|*/
  /* _____________________________________________________*/
  /* 1. Сохранение, удаление                              */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Сохранение футера 
   public function saveFooter(Request $request) {
      $footer = Footer::find(1);
      $footerUpdate = $footer->update([
         'title' => $request->title,
         'titleDesc' => $request->titleDesc,
         'license' => $request->license,
         'licenseDesc' => $request->licenseDesc,
         'footer' => $request->footer,
      ]);
      
      /* Проверка обновления футера */
      if ($footerUpdate) {
         return true;
      } else {
         return false;
      };
   }
}
