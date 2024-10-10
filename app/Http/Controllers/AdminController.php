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
use App\Models\SpecialistEducation;
use App\Models\Work;
use App\Models\SpecialistWork;

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
         if ($validated->fails()) return response()->json([
            "status" => false,
            "message" => "Файл не прошёл проверку.",
            "data" => null,
         ]);;

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

         if ($path) {
            return response()->json([
               "status" => true,
               "message" => "Файл загружен.",
               "data" => Storage::url($path),
            ]);
         } else {
            return response()->json([
               "status" => false,
               "message" => "Файл не удалось загрузить.",
               "data" => null,
            ]);
         };
      } else {
         return response()->json([
            "status" => false,
            "message" => "Отсутствует файл.",
            "data" => null,
         ]);
      };      
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
   /* Модульное сохранение */
   public function saveSpecialistModular(Request $request) {
      $result = null;
      
      switch ($request->type) {
         case 'profile':
            $result = $this->saveSpecialistProfile($request);            
            return $result;
            
            break;         
         case 'specializations':
            $result = $this->saveSpecialistSpecializations($request);
            return $result;
            
            break;         
         default:
            break;
      }
   }
   public function saveSpecialistProfile(Request $request) {
      return (object) [
         "status" => true,
         "message" => "Успешно",
      ];
   }
   public function saveSpecialistSpecializations(Request $request) {
      return (object) [
         "status" => true,
         "message" => "Успешно",
      ];
   }






   /* FIXME Добавить проверок при помощи валидатора на количество символов */
   /* Сохранение специалистов */ 
   public function saveSpecialistsChanges(Request $request) {
      // Удаление помеченных
      foreach ($request->specialists as $key => $value) {
         if ($value['delete'] === true) {
            $specialist = Specialist::find($value['id']);
            $specialist->delete();
            continue;
         };

         $specialist = Specialist::find($value['id']);
         $specialist->update([
            'hide' => $value['hide'],
         ]);
      };      
   }
   /* Сохранение всех данных специалиста */ 
   public function saveSpecialistProfileChanges(Request $request) {
      $path = null;

      if($request->hasFile('image')) {
         $validated = validator($request->all(), [
            'image' => [
               'required',
               File::types('png')->max(10 * 1024),
            ],
         ]);
         if ($validated->fails()) return response()->json([
            "status" => false,
            "message" => "Файл не прошёл проверку.",
            "data" => null,
         ]);

         $path = $request->file('image')->store(
            'public/specialists'
         );

         if (!$path) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось сохранить изображение.",
               "data" => null,
            ]);
         }
      };      

      $profile = json_decode($request->profile);
      $specialist = Specialist::find($profile->id->body);

      if ($specialist) {
         $specialist->update([
            'link' => $profile->link->body,
            'family' => $profile->family->body,
            'name' => $profile->name->body,
            'surname' => $profile->surname->body,
            'category' => $profile->category->body,
            'degree' => $profile->degree->body,
            'rank' => $profile->rank->body,
            'startWorkAge' => $profile->startWorkAge->body ? $profile->startWorkAge->body : null,
            'startWorkCity' => $profile->startWorkCity->body,
            'adultDoctor' => $profile->adultDoctor->body,
            'childrenDoctor' => $profile->childrenDoctor->body,
         ]);

         if($path) $specialist->update(['filename' => str_replace("public/specialists/", "", $path)]);

         return response()->json([
            "status" => true,
            "message" => "Данные о профиле специалиста обновлены.",
            "data" => $path ? Storage::url($path) : null,
         ]);   
      } else {
         return response()->json([
            "status" => false,
            "message" => "Врач не найден.",
            "data" => null,
         ]);   
      };
   }
   /* Добавление нового специалиста */ 
   public function addSpecialist(Request $request) {
      $path = null;

      if($request->hasFile('image')) {
         $validated = validator($request->all(), [
            'image' => [
               'required',
               File::types('png')->max(10 * 1024),
            ],
         ]);
         if ($validated->fails()) return response()->json([
            "status" => false,
            "message" => "Файл не прошёл проверку.",
            "data" => null,
         ]);

         $path = $request->file('image')->store(
            'public/specialists'
         );

         if (!$path) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось сохранить изображение.",
               "data" => null,
            ]);
         };
      };      

      $profile = json_decode($request->profile);
      $specialist = Specialist::create([
         "link" => $profile->link->body,
         "family" => $profile->family->body,
         "name" => $profile->name->body,
         "surname" => $profile->surname->body,
         "category" => $profile->category->body,
         "degree" => $profile->degree->body,
         "rank" => $profile->rank->body,
         "startWorkAge" => $profile->startWorkAge->body ? $profile->startWorkAge->body : null,
         "startWorkCity" => $profile->startWorkCity->body,
         "adultDoctor" => true,
         "childrenDoctor" => false,
         "filename" => str_replace("public/specialists/", "", $path),
      ]);

      if ($specialist) {
         return response()->json([
            "status" => true,
            "message" => "Специалист создан.",
            "data" => (object) [
               "id" => $specialist->id,
               "path" => Storage::url($path),
            ],
         ]);   
      } else {
         return response()->json([
            "status" => false,
            "message" => "Не удалось создать специалиста.",
            "data" => null,
         ]);   
      };
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
            $certificate = Certificate::find($value['id']);
            $certificateUpdate = $certificate->update([
               "name" => $value['name'],
               "organization" => $value['organization'],
               "endEducation" => $value['endEducation'],
            ]);           

            if(!$certificateUpdate) {
               return response()->json([
                  "status" => false,
                  "message" => "Не удалось обновить значение.",
               ]);      
            }
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
   public function saveSpecialistEducationsChanges(Request $request) {
      // Проверка на существование
      if(Specialist::find($request->id)) {         
         $arrayID = [];

         foreach ($request->array as $key => $value) {
            // Удаление
            if ($value["delete"] === true){
               $education = Education::find($value['id']);
               $education->delete();
               continue;
            }         

            // Создание
            if ($value['create'] === true) {
               $educationCreate = Education::create([
                  "name" => $value['name'],
                  "organization" => $value['organization'],
                  "date" => $value['date'],
                  "speсialization" => $value['speсialization'],
               ]);

               if($educationCreate) {
                  SpecialistEducation::create([
                     'id_specialist' => $request->id,
                     'id_education' => $educationCreate->id,
                  ]);   
               }
   
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value['id'], 
                  // Новый id
                  'new' => $educationCreate->id
               ];            
               continue;
            };       

            // Обновление
            $education = Education::find($value['id']);
            $educationUpdate = $education->update([
               "name" => $value['name'],
               "organization" => $value['organization'],
               "date" => $value['date'],
               "speсialization" => $value['speсialization'],
            ]);      
            
            if(!$educationUpdate) {
               return response()->json([
                  "status" => false,
                  "message" => "Не удалось обновить значение.",
               ]);      
            }
         }

         return response()->json([
            "status" => true,
            "message" => "Данные об обучении сохранились.",
            "data" => $arrayID,
         ]);
      } else {
         return response()->json([
            "status" => false,
            "message" => "Специалист не найден.",
         ]);
      }      
   }
   public function saveSpecialistWorksChanges(Request $request) {
      // Проверка на существование
      if(Specialist::find($request->id)) {         
         $arrayID = [];

         foreach ($request->array as $key => $value) {
            // Удаление
            if ($value["delete"] === true){
               $work = Work::find($value['id']);
               $work->delete();
               continue;
            }         

            // Создание
            if ($value['create'] === true) {
               $workCreate = Work::create([
                  "name" => $value['name'],
                  "organization" => $value['organization'],
                  "startWork" => $value['startWork'],
                  "endWork" => $value['endWork'],
               ]);

               if($workCreate) {
                  SpecialistWork::create([
                     'id_specialist' => $request->id,
                     'id_work' => $workCreate->id,
                  ]);   
               }
   
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value['id'], 
                  // Новый id
                  'new' => $workCreate->id
               ];            
               continue;
            };       

            // Обновление
            $work = Work::find($value['id']);
            $workUpdate = $work->update([
               "name" => $value['name'],
               "organization" => $value['organization'],
               "startWork" => $value['startWork'],
               "endWork" => $value['endWork'],
            ]);           
         
            if(!$workUpdate) {
               return response()->json([
                  "status" => false,
                  "message" => "Не удалось обновить значение.",
               ]);      
            }
         }

         return response()->json([
            "status" => true,
            "message" => "Данные о прошлых работах сохранились.",
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
               "speсialization" => $value['speсialization'],
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
            "speсialization" => $value['speсialization'],
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
