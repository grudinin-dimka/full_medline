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
use App\Models\Education;
use App\Models\Work;
use App\Models\Certificate;

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
   /* |                    ГЛАВНАЯ                        |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Слайдер                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение содержимого всех слайдов */
   public function saveSlidesChanges(Request $request) {
      // Удаление слайдов, подлежащих удалению
      foreach ($request->slides as $key => $value) {
         if ($value['delete'] === true) {
         $slide = Slide::find($value['id']);
         $slide->delete();
         };
      };

      $arrayID = [];
      // Добавление новых слайдов
      foreach ($request->slides as $key => $value) {
         if ($value['create'] === true){
            $slideCreate = Slide::create([
               'name' => $value['name'],
               'link' => $value['link'],
               'filename' => $value['filename'],
               'hide' => $value['hide'],
               'order' => $value['order'],
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'], 
               // Новый id
               'new' => $slideCreate->id
            ];
         };
      };

      // Обновление существующих слайдов
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

      // Сортировка слайдов по порядку от наименьшего до наибольшого
      $slides = Slide::all()->SortBy('order');

      // Обновление порядковых номеров
      $count = 0;
      foreach ($slides as $slideKey => $slideValue) {
         $count++;
         $slideValue->order = $count;
         $slideValue->save();
      };

      // Получение всех файлов
      $filesSlides = Storage::files('public/slides');

      foreach ($filesSlides as $fileKey => $fileValue) {
         $useFile = false;
         /* Проверка на использование файла */
         foreach ($slides as $slideKey => $slideValue) {
         /* Обрезание значения $fileValue до названия файла */
         $str = str_replace('public/slides/', '', $fileValue);
         /* Проверка значения названия файла на совпадение */
         if ($slideValue->filename == $str) {
            $useFile = true;
         };
         };

         /* Удаление файла, если не используется */
         if (!$useFile) {
         Storage::delete($fileValue);
         };
      };

      return $arrayID;
   } 
   /* _____________________________________________________*/
   /* 2. Футер                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение футера */ 
   public function saveFooter(Request $request) {
      $footer = Footer::find(1);
      $footerUpdate = $footer->update([
         'title' => $request->title,
         'titleDesc' => $request->titleDesc,
         'license' => $request->license,
         'licenseDesc' => $request->licenseDesc,
         'footer' => $request->footer,
      ]);
      
      // Проверка обновления футера
      if ($footerUpdate) {
         return true;
      } else {
         return false;
      };
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                  СПЕЦИАЛИСТЫ                      |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Врачи                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение специалистов */ 
   public function saveSpecialistsChanges(Request $request) {
      // Удаление специалистов, подлежащих удалению
      foreach ($request->specialists as $key => $value) {
         if ($value['delete'] === true) {
            var_dump('id: ' . $value['id']);
            $specialist = Specialist::find($value['id']);
            $specialist->delete();
         };
      };
      
      // Добавление новых специалистов
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
   /* Сохранение специалистов */ 
   public function saveSpecialistsHides(Request $request) {
      foreach ($request->specialists as $key => $value) {
         $specialist = Specialist::find($value['id']);
         $specialist->hide = $value['hide'];
         $specialist->save();
      }

      return true;
   }
   /* _____________________________________________________*/
   /* 2. Специализации                                     */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений специализаций */
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

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'], 
               // Новый id
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
   /* _____________________________________________________*/
   /* 3. Клиники                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений клиник */
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

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'], 
               // Новый id
               'new' => $clinicToCreate->id
            ];            
         };       
      }

      // Обновление данных 
      foreach ($request->clinics as $key => $value) {
         if ($value["delete"] !== true && $value['create'] !== true){
            $clinic = Clinic::find($value['id']);
            $clinicUpdate = $clinic->update([
               "name" => $value['name'],
               "city" => $value['city'],
               "street" => $value['street'],
               "home" => $value['home'],
               "index" => $value['index'],
               "geoWidth" => $value['geoWidth'],
               "geoLongitude" => $value['geoLongitude'],
            ]);     
         }         
      }

      return $arrayID;
   }
   /* _____________________________________________________*/
   /* 4. Образования                                       */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений образований */
   public function saveEducationsChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->educations as $key => $value) {
         if ($value["delete"] === true){
            $education = Education::find($value['id']);
            $education->delete();
         }         
      }

      $arrayID = [];
      // Добавление новых
      foreach ($request->educations as $key => $value) {
         if ($value["create"] === true) {
            $educationToCreate = Education::create([
               "name" => $value['name'],
               "organization" => $value['organization'],
               "date" => $value['date'],
               "id_specialization" => $value['id_specialization'],
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'], 
               // Новый id
               'new' => $educationToCreate->id
            ];            
         };       
      }

      // Обновление данных 
      foreach ($request->educations as $key => $value) {
         if ($value["delete"] !== true && $value['create'] !== true){
            $education = Education::find($value['id']);
            $educationUpdate = $education->update([
               "name" => $value['name'],
               "organization" => $value['organization'],
               "date" => $value['date'],
               "id_specialization" => $value['id_specialization'],
            ]);     
         }         
      }

      return $arrayID;      
   }
   /* _____________________________________________________*/
   /* 5. Прошлые работы                                    */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений работ */
   public function saveWorkChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->works as $key => $value) {
         if ($value["delete"] === true){
            $work = Work::find($value['id']);
            $work->delete();
         }         
      }

      $arrayID = [];
      // Добавление новых
      foreach ($request->works as $key => $value) {
         if ($value["create"] === true) {
            $workToCreate = Work::create([
               "startWork" => $value['startWork'],
               "endWork" => $value['endWork'],
               "organization" => $value['organization'],
               "name" => $value['name'],
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'], 
               // Новый id
               'new' => $workToCreate->id
            ];            
         };       
      }

      // Обновление данных 
      foreach ($request->works as $key => $value) {
         if ($value["delete"] !== true && $value['create'] !== true){
            $work = Work::find($value['id']);
            $workUpdate = $work->update([
               "startWork" => $value['startWork'],
               "endWork" => $value['endWork'],
               "organization" => $value['organization'],
               "name" => $value['name'],
            ]);     
         }         
      }
      
      return $arrayID;      
   }   
   /* _____________________________________________________*/
   /* 6. Сертификаты                                       */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений сертификатов */
   public function saveCertificatesChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->certificates as $key => $value) {
         if ($value["delete"] === true){
            $certificate = Certificate::find($value['id']);
            $certificate->delete();
         }         
      }

      $arrayID = [];
      // Добавление новых
      foreach ($request->certificates as $key => $value) {
         if ($value["create"] === true) {
            $certificateToCreate = Certificate::create([
               "organization" => $value['organization'],
               "endEducation" => $value['endEducation'],
               "name" => $value['name'],
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value['id'], 
               // Новый id
               'new' => $certificateToCreate->id
            ];            
         };       
      }

      return $arrayID;      
   }
}
