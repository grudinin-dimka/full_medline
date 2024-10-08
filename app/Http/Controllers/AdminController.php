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
use App\Models\SpecialistSpecialization;
use App\Models\Clinic;
use App\Models\SpecialistClinic;
use App\Models\Certificate;
use App\Models\SpecialistCertificate;
use App\Models\Education;
use App\Models\Work;

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
   /* FIXME Добавить проверок при помощи валидатора на количество символов */
   /* _____________________________________________________*/
   /* 1. Слайдер                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение содержимого всех слайдов */
   public function saveSlidesChanges(Request $request) {
      $arrayID = [];

      foreach ($request->slides as $key => $value) {
         // Удаление
         if ($value['delete'] === true) {
            $slide = Slide::find($value['id']);
            $slide->delete();
            continue;
         };

         // Добавление
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
            continue;
         };

         // Обновление
         $slide = Slide::find($value['id']);
         $slideUpdate = $slide->update([
            'name' => $value['name'],
            'link' => $value['link'],
            'filename' => $value['filename'],
            'hide' => $value['hide'],
            'order' => $value['order'],
         ]);  
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
   /* FIXME Добавить проверок при помощи валидатора на количество символов */
   /* Сохранение специалистов */ 
   public function saveSpecialistsChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->specialists as $key => $value) {
         if ($value['delete'] === true) {
            $specialist = Specialist::find($value['id']);
            $specialist->delete();
         };
      };      

      // Обновление данных
      foreach ($request->specialists as $key => $value) {
         if ($value['delete'] !== true) {
            $specialist = Specialist::find($value['id']);
            $specialist->update([
               'hide' => $value['hide'],
            ]);
         };
      };      
   }
   /* Сохранение всех данных специалиста */ 
   public function saveSpecialistProfileChanges(Request $request) {
      $specialist = Specialist::find($request->array["profile"]["id"]["body"]);
      
      if ($specialist) {
         $specialist->update([
            'link' => $request->array["profile"]["link"]["body"],
            'family' => $request->array["profile"]["family"]["body"],
            'name' => $request->array["profile"]["name"]["body"],
            'surname' => $request->array["profile"]["surname"]["body"],
            'category' => $request->array["profile"]["category"]["body"],
            'degree' => $request->array["profile"]["degree"]["body"],
            'rank' => $request->array["profile"]["rank"]["body"],
            'startWorkAge' => $request->array["profile"]["startWorkAge"]["body"],
            'startWorkCity' => $request->array["profile"]["startWorkCity"]["body"],
            'adultDoctor' => $request->array["profile"]["adultDoctor"]["body"],
            'childrenDoctor' => $request->array["profile"]["childrenDoctor"]["body"],
         ]);

         return response()->json([
            "status" => true,
            "message" => "Данные профиля врача обновлены.",
            "data" => $specialist,
         ]);   
      } else {
         return response()->json([
            "status" => false,
            "message" => "Врач не найден.",
            "data" => $request->all(),
         ]);            
      }
   }
   /* FIXME Добавить проверок при помощи валидатора на количество символов */
   /* Сохранение специализаций специалиста */ 
   public function saveSpecialistSpecializationsChanges(Request $request) {
      // Проверка на существование
      if(Specialist::find($request->id)) {         
         // Поиск или добавление 
         foreach ($request->array as $key => $value) {
            SpecialistSpecialization::firstOrCreate([
               'id_specialist' => $value["id_specialist"],
               'id_specialization' => $value["id_specialization"], 
            ]);
         };

         // Удаление ненужных записей
         $specialistSpecializations = SpecialistSpecialization::where('id_specialist', $request->id)->get();
         foreach ($specialistSpecializations as $key => $value) {
            // Поиск в массиве
            $status = false;
            foreach ($request->array as $key => $valueReqArr) {
               if (
                  $value["id_specialist"] === $valueReqArr["id_specialist"] 
                     && 
                  $value["id_specialization"] === $valueReqArr["id_specialization"]
               ) {
                  $status = true;
               };
            }

            if (!$status) {
               $value->delete();
            };
         };
         
         return response()->json([
            "status" => true,
            "message" => "Данные о специализациях сохранились.",
         ]);
      } else {
         return response()->json([
            "status" => false,
            "message" => "Специалист не найден.",
         ]);
      }
   }
   /* Сохранение клиник специалиста */ 
   public function saveSpecialistClinicsChanges(Request $request) {
      // Проверка на существование
      if(Specialist::find($request->id)) {         
         // Поиск или добавление 
         foreach ($request->array as $key => $value) {
            SpecialistClinic::firstOrCreate([
               'id_specialist' => $value["id_specialist"],
               'id_clinic' => $value["id_clinic"], 
               "priem" => $value["priem"],
            ]);
         };

         // Удаление ненужных записей
         $specialistClinics = SpecialistClinic::where('id_specialist', $request->id)->get();
         foreach ($specialistClinics as $key => $value) {
            // Поиск в массиве
            $status = false;
            foreach ($request->array as $key => $valueReqArr) {
               if (
                  $value["id_specialist"] === $valueReqArr["id_specialist"] 
                     && 
                  $value["id_clinic"] === $valueReqArr["id_clinic"]
                     &&
                  $value["priem"] === $valueReqArr["priem"]
               ) {
                  $status = true;
               };
            }

            if (!$status) {
               $value->delete();
            };
         };
         
         return response()->json([
            "status" => true,
            "message" => "Данные о клиниках сохранились.",
         ]);
      } else {
         return response()->json([
            "status" => false,
            "message" => "Специалист не найден.",
         ]);
      }
   }
   public function saveSpecialistCertificatesChanges(Request $request) {
      // Проверка на существование
      if(Specialist::find($request->id)) {         
         $arrayID = [];

         foreach ($request->array as $key => $value) {
            // Удаление
            if ($value["delete"] === true){
               $certificate = Certificate::find($value['id']);
               $certificate->delete();
               continue;
            }         

            // Создание
            if ($value['create'] === true) {
               $certificateCreate = Certificate::create([
                  "name" => $value['name'],
                  "organization" => $value['organization'],
                  "endEducation" => $value['endEducation'],
               ]);

               if($certificateCreate) {
                  SpecialistCertificate::create([
                     'id_specialist' => $request->id,
                     'id_certificate' => $certificateCreate->id,
                  ]);   
               }
   
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value['id'], 
                  // Новый id
                  'new' => $certificateCreate->id
               ];            
               continue;
            };  

            // Обновление
            $clinic = Clinic::find($value['id']);
            $clinicUpdate = $clinic->update([
               "name" => $value['name'],
               "organization" => $value['organization'],
               "endEducation" => $value['endEducation'],
            ]);           
         }

         return response()->json([
            "status" => true,
            "message" => "Данные о сертификатах сохранились.",
            "data" => $arrayID,
         ]);
      } else {
         return response()->json([
            "status" => false,
            "message" => "Специалист не найден.",
         ]);
      }
   }
   /* _____________________________________________________*/
   /* 2. Специализации                                     */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений специализаций */
   public function saveSpecializationsChanges(Request $request) {
      $arrayID = [];
      
      foreach ($request->specializations as $key => $value) {
         // Удаление
         if ($value["delete"] === true){
            $specialization = Specialization::find($value['id']);
            $specialization->delete();
            continue;
         };       

         // Создание
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
            continue;
         };       

         // Обновление
         $specialization = Specialization::find($value['id']);
         $specializationUpdate = $specialization->update([
            'name' => $value['name'],
         ]);     
      }
      
      return $arrayID;
   }
   /* _____________________________________________________*/
   /* 3. Клиники                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений клиник */
   public function saveClinicsChanges(Request $request) {
      $arrayID = [];

      foreach ($request->clinics as $key => $value) {
         // Удаление
         if ($value["delete"] === true){
            $clinic = Clinic::find($value['id']);
            $clinic->delete();
            continue;
         }         

         // Создание
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
            continue;
         };       

         // Обновление
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

      return $arrayID;
   }
   /* _____________________________________________________*/
   /* 4. Образования                                       */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений образований */
   public function saveEducationsChanges(Request $request) {
      $arrayID = [];

      foreach ($request->educations as $key => $value) {
         // Удаление
         if ($value["delete"] === true){
            $education = Education::find($value['id']);
            $education->delete();
            continue;
         }         

         // Создание
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
            continue;
         };       

         // Обновление
         $education = Education::find($value['id']);
         $educationUpdate = $education->update([
            "name" => $value['name'],
            "organization" => $value['organization'],
            "date" => $value['date'],
            "id_specialization" => $value['id_specialization'],
         ]);     
      }
      
      return $arrayID;      
   }
   /* _____________________________________________________*/
   /* 5. Прошлые работы                                    */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений работ */
   public function saveWorkChanges(Request $request) {
      $arrayID = [];

      foreach ($request->works as $key => $value) {
         // Удаление
         if ($value["delete"] === true){
            $work = Work::find($value['id']);
            $work->delete();
            continue;
         }         

         // Создание
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
            continue;     
         };       

         // Обновление
         $work = Work::find($value['id']);
         $workUpdate = $work->update([
            "startWork" => $value['startWork'],
            "endWork" => $value['endWork'],
            "organization" => $value['organization'],
            "name" => $value['name'],
         ]);     
      }
      
      return $arrayID;      
   }   
   /* _____________________________________________________*/
   /* 6. Сертификаты                                       */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение изменений сертификатов */
   public function saveCertificatesChanges(Request $request) {
      $arrayID = [];

      foreach ($request->certificates as $key => $value) {
         // Удаление
         if ($value["delete"] === true){
            $certificate = Certificate::find($value['id']);
            $certificate->delete();
            continue;
         }         

         // Создание
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
            continue;        
         };
         
         // Обновление
         $certificate = Certificate::find($value['id']);
         $certificateUpdate = $certificate->update([
            "organization" => $value['organization'],
            "endEducation" => $value['endEducation'],
            "name" => $value['name'],
         ]);     
      }

      return $arrayID;      
   }
}
