<?php
namespace App\Http\Controllers;

/* Контроллер */
use App\Http\Controllers\HomeController;

/* Подключения */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

/* Помощники */
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
Use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

/* Модели */
use App\Models\Rights;
use App\Models\Status;
use App\Models\User;

use App\Models\Slide;
use App\Models\Footer;

use App\Models\About;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\ContactPhone;
use App\Models\Mail;
use App\Models\ContactMail;

use App\Models\Specialist;
use App\Models\Specialization;
use App\Models\SpecialistSpecialization;
use App\Models\Clinic;
use App\Models\SpecialistClinic;
use App\Models\Certificate;
use App\Models\SpecialistCertificate;
use App\Models\Work;
use App\Models\SpecialistWork;
use App\Models\Education;
use App\Models\SpecialistEducation;

use App\Models\Shedule;
use App\Models\ShedulesClinic;
use App\Models\ShedulesCurrentDay;
use App\Models\ShedulesDay;
use App\Models\ShedulesDaysTime;

use App\Models\PriceFile;
use App\Models\PriceAddress;
use App\Models\PriceCategory;
use App\Models\PriceValue;

use App\Models\News;
use App\Models\Video;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Exception;
use Throwable;
use ZipArchive;
use XMLWriter;
use Transliterator;

use function PHPUnit\Framework\isEmpty;

class AdminController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ОБЩИЕ                         |*/
   /* |___________________________________________________|*/
   /* Загрузка файла на сервер */ 
   public function uploadFile(Request $request) {
      /* Проверка на наличие переменной image с файлом в запросе */  
		if($request->hasFile('file')) {
         $file = $request->file('file');
         
         // Проверка на картинку
         $isImage = str_starts_with($file->getMimeType(), 'image/');

         // Проверка на видео
         $isVideo = str_starts_with($file->getMimeType(), 'video/');

         // Правила
         $rules = [
            'type' => 'required',
            'formats' => 'required',
         ];

         // Сообщения
         $messages = [
            'type.required' => 'Тип не указан.',
            'formats.required' => 'Форматы не указаны.',
         ];

         // Добавление правил в зависимости от типа
         if ($isImage) {
            $rules['file'] = [
               'required', // общие правила
               File::image()
                  ->types($request->formats)
                  ->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500)),
               'max:' . (5 * 1024),
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.types'] = 'Файл не соответствует форматам.';
            $messages['file.dimensions'] = 'Файл превышает допустимое разрешение 1500х1500.';
            $messages['file.max'] = 'Файл превышает допустимый размер 5 мб.';
         } else if ($isVideo) {
            $rules['file'] = [
               'required', // общие правила
               'mimetypes:video/mp4,video/quicktime,video/webm',
               'max:' . (100 * 1024),
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.mimetypes'] = 'Файл не соответствует форматам.';
            $messages['file.max'] = 'Файл превышает допустимый размер 100 мб.';
         } else {
            $rules['file'] = [
               'required', // общие правила
               File::types($request->formats),
               'max:' . (2 * 1024),
            ];

            $messages['file.required'] = 'Файл не указан.';
            $messages['file.types'] = 'Файл не соответствует форматам.';
            $messages['file.max'] = 'Файл превышает допустимый размер 2 мб.';
         };

         $validator = Validator::make($request->all(), $rules, $messages);

         if ($validator->fails()) return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Файл не прошёл проверку.",
            "result" => null,
         ], 422);

         // Получение оригинального названия
         $originalName = $file->getClientOriginalName();

         switch ($request->type) {
            case 'prices':
               $path = $request->file('file')->storeAs(
                  'public/prices',
                  $originalName, 
                  'local'
               );
               break;
            default:
               $path = $request->file('file')->store(
                  'public/' . $request->type
               );               
               break;
         }

         if ($path) {
            return response()->json([
               "success" => true,
               "debug" => false,
               "message" => "Файл загружен.",
               "result" => Storage::url($path),
            ], 200);
         } else {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Файл не удалось загрузить.",
               "result" => null,
            ], 500);
         };
      } else {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Отсутствует файл.",
            "result" => null,
         ], 422);
      };      
   } 
   
   /* Получение данных о профиле пользователя */
   public function getProfileInfo(Request $request) {
      $user = $request->user();
      $user->status = Status::find($user->statusId)->name;
      $user->rights = Rights::find($user->rightsId)->name;
      $user->path = Storage::url('users/' . $user->filename);

      return response()->json([
         "status" => true,
         "message" => "Данные успешно получены.",
         "data" => $user,
      ]);
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    ГЛАВНАЯ                        |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Слайдер                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение содержимого всех слайдов */
   public function saveSlidesChanges(Request $request) {
      $validator = Validator::make($request->all(), [
         'slides' => 'nullable|array',
      ], [
         'array' => 'Поле :attribute должно быть массивом.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

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
      if($filesSlides) {
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
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "result" => $arrayID
      ], 200);
   } 

   /* _____________________________________________________*/
   /* 2. Футер                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Сохранение футера */ 
   public function saveFooter(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'description' => 'required|string',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'string' => 'Поле :attribute должно быть строкой.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };
      
      try {
         $footer = Footer::find(1);

         $footerUpdate = $footer->update([
            'description' => $request->description,
         ]);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Футер не найден.",
            "result" => null
         ], 500);
      };
      
      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "data" => null         
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                  СПЕЦИАЛИСТЫ                      |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Врачи                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Модульное сохранение */
   public function saveSpecialistModular(Request $request) {
      // Сохранение профиля
      $saveProfile = $this->saveSpecialistProfile($request);            
      if (!$saveProfile['object']->success) {
         return response()->json($saveProfile['object'], $saveProfile['status']);
      };

      // Сохранение специализаций
      $saveSpecializations = $this->saveSpecialistSpecializations($request);            
      if (!$saveSpecializations['object']->success) {
         return response()->json($saveSpecializations['object'], $saveSpecializations['status']);
      };
      
      // Сохранение клиник
      $saveClinic = $this->saveSpecialistClinics($request);            
      if (!$saveClinic['object']->success) {
         return response()->json($saveClinic['object'], $saveClinic['status']);
      };
      
      // Сохранение сертификатов
      $saveClinic = $this->saveSpecialistCertificates($request);            
      if (!$saveClinic['object']->success) {
         return response()->json($saveClinic['object'], $saveClinic['status']);
      };
      
      // Сохранение образований
      $saveEducation = $this->saveSpecialistEducations($request);            
      if (!$saveEducation['object']->success) {
         return response()->json($saveEducation['object'], $saveEducation['status']);
      };
      
      // Сохранение работ
      $saveWork = $this->saveSpecialistWorks($request);            
      if (!$saveWork['object']->success) {
         return response()->json($saveWork['object'], $saveWork['status']);
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Все данные специалиста сохранены.",
         "result" => [
            "imagePath" => $saveProfile['object']->result,
            "certificates" => $saveClinic['object']->result,
            "educations" => $saveEducation['object']->result,
            "works" => $saveWork['object']->result,     
         ],
      ]);  
   }

   /* Модуль: Сохранение профиля */ 
   public function saveSpecialistProfile(Request $request) {
      $profile = json_decode($request->profile);
      
      $path = null;
      if($request->hasFile('image')) {
         $validated = validator($request->all(), [
            'image' => [
               'required',
               File::image()
                  ->types(['png', 'webp'])
                  ->max(5 * 1024)
                  ->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500)),
            ],
         ], [
            'image.required' => 'Файл обязательно.',
            'image.image' => 'Файл должен быть изображением.',
            'image.max' => 'Файл не должен быть больше 5МБ.',
            'image.dimensions' => 'Размер изображения не должен превышать 1500x1500px.',
         ]);

         if ($validated->fails()) return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "errors" => $validated->errors(),
               "message" => "Файл не прошёл проверку.",
               "result" => null,
            ],
            "status" => 422,
         ];

         $path = $request->file('image')->store(
            'public/specialists'
         );
         
         if (!$path) {
            return [
               "object" => (object) [                  
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось сохранить изображение.",
                  "result" => null,
               ],
               "status" => 500,
            ];
         }
      };      

      $specialist = Specialist::find($profile->id->value);
      if ($specialist) {
         $specialist->update([
            'link' => $profile->link->value,
            'family' => $profile->family->value,
            'name' => $profile->name->value,
            'surname' => $profile->surname->value,
            'description' => $profile->description->value,
            'category' => $profile->category->value,
            'degree' => $profile->degree->value,
            'rank' => $profile->rank->value,
            'startWorkAge' => $profile->startWorkAge->value ? $profile->startWorkAge->value : null,
            'startWorkCity' => $profile->startWorkCity->value,
            'adultDoctor' => $profile->adultDoctor->value,
            'childrenDoctor' => $profile->childrenDoctor->value,
            'childrenDoctorAge' => $profile->childrenDoctor->value ? $profile->childrenDoctorAge->value : 0,
         ]);

         if ($path) $specialist->update(['filename' => str_replace("public/specialists/", "", $path)]);

         return [
            "object" => (object) [
               "success" => true,
               "debug" => true,
               "message" => "Данные о профиле специалиста обновлены.",
               "result" => $path ? Storage::url($path) : null,
            ],
            "status" => 200,
         ];   
      } else {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Специалист не найден.",
               "result" => null,
            ],
            "status" => 500,
         ];   
      };
   }

   /* Модуль: Сохранение специализации */ 
   public function saveSpecialistSpecializations(Request $request) {
      $specializations = json_decode($request->specializations);
      $id = json_decode($request->id);
      
      $specialist = Specialist::find($id);
      if($specialist) {         
         // Поиск или добавление 
         foreach ($specializations as $key => $value) {
            SpecialistSpecialization::firstOrCreate([
               'id_specialist' => $value->id_specialist,
               'id_specialization' => $value->id_specialization, 
            ]);
         };

         // Поиск в массиве
         $specialistSpecializations = SpecialistSpecialization::where('id_specialist', $id)->get();
         
         if($specialistSpecializations) {
            // Удаление ненужных записей
            foreach ($specialistSpecializations as $key => $value) {
               // Поиск в массиве
               $status = false;
               foreach ($specializations as $key => $valueReqArr) {
                  if (
                     $value->id_specialist === $valueReqArr->id_specialist 
                        && 
                     $value->id_specialization === $valueReqArr->id_specialization
                  ) {
                     $status = true;
                  };
               }
   
               if (!$status) {
                  $value->delete();
               };
            };
            
            return [
               "object" => (object) [
                  "success" => true,
                  "debug" => true,
                  "message" => "Данные о специализациях сохранились.",
                  "result" => null,
               ],
               "status" => 200,
            ];
         } else {
            return [
               "object" => (object) [
                  "success" => false,
                  "debug" => true,
                  "message" => "При поиске данных о специализациях возникли проблемы.",
                  "result" => null,
               ],
               "status" => 500,
            ];
         };
      } else {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Специалист не найден.",
               "result" => null,
            ],
            "status" => 500,
         ];
      }
   }

   /* Модуль: Сохранение клиник */
   public function saveSpecialistClinics(Request $request) {
      $clinics = json_decode($request->clinics);
      $id = json_decode($request->id);
      
      $specialist = Specialist::find($id);
      
      if($specialist) {         
         // Поиск или добавление 
         foreach ($clinics as $key => $value) {
            SpecialistClinic::firstOrCreate([
               'id_specialist' => $value->id_specialist,
               'id_clinic' => $value->id_clinic, 
               "priem" => $value->priem,
            ]);
         };

         // Удаление ненужных записей
         $specialistClinics = SpecialistClinic::where('id_specialist', $id)->get();
         foreach ($specialistClinics as $specialistClinicskey => $specialistClinicsValue) {
            // Поиск в массиве
            $status = false;
            
            foreach ($clinics as $key => $valueReqArr) {
               if (
                  $specialistClinicsValue->id_specialist === $valueReqArr->id_specialist 
                     && 
                  $specialistClinicsValue->id_clinic === $valueReqArr->id_clinic
                     &&
                  $specialistClinicsValue->priem === $valueReqArr->priem
               ) {
                  $status = true;
               };
            }

            if (!$status) {
               $specialistClinicsValue->delete();
            };
         };
         
         return [
            "object" => (object) [
               "success" => true,
               "debug" => true,
               "message" => "Данные о клиниках сохранились.",
               "result" => null,
            ],
            "status" => 200,
         ];
      } else {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Специалист не найден.",
               "result" => null,
            ],
            "status" => 500,
         ];
      };
   }

   /* Модуль: Сохранение сертификатов */
   public function saveSpecialistCertificates(Request $request) {
      $certificates = json_decode($request->certificates);
      $id = json_decode($request->id);
      
      $specialist = Specialist::find($id);
      if($specialist) {         
         $arrayID = [];
         foreach ($certificates as $key => $value) {
            // Удаление
            if ($value->delete === true){
               $certificate = Certificate::find($value->id);
               $certificate->delete();
               continue;
            }         

            // Создание
            if ($value->create === true) {
               $certificateCreate = Certificate::create([
                  "name" => $value->name,
                  "organization" => $value->organization,
                  "endEducation" => $value->endEducation,
               ]);

               if($certificateCreate) {
                  SpecialistCertificate::create([
                     'id_specialist' => $id,
                     'id_certificate' => $certificateCreate->id,
                  ]);   
               }
   
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value->id, 
                  // Новый id
                  'new' => $certificateCreate->id
               ];            
               continue;
            };  

            // Обновление
            $certificate = Certificate::find($value->id);
            $certificateUpdate = $certificate->update([
               "name" => $value->name,
               "organization" => $value->organization,
               "endEducation" => $value->endEducation,
            ]);           

            if(!$certificateUpdate) {
               return [
                  "object" => (object) [
                     "success" => false,
                     "debug" => true,
                     "message" => "Не удалось обновить значение.",
                     "result" => null,
                  ],
                  "status" => 500,
               ];
            }
         };

         return [
            "object" => (object) [
               "success" => true,
               "debug" => false,
               "message" => "Данные о сертификатах сохранились.",
               "result" => $arrayID,
            ],
            "status" => 200,
         ];         
      } else {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Специалист не найден.",
               "result" => null,
            ],
            "status" => 500,
         ];         
      }
   }

   /* Модуль: Сохранение образований */
   public function saveSpecialistEducations(Request $request) {
      $educations = json_decode($request->educations);
      $id = json_decode($request->id);
      
      $specialist = Specialist::find($id);
      if($specialist) {         
         $arrayID = [];

         foreach ($educations as $key => $value) {
            // Удаление
            if ($value->delete === true){
               $education = Education::find($value->id);
               $education->delete();
               continue;
            }         

            // Создание
            if ($value->create === true) {
               $educationCreate = Education::create([
                  "name" => $value->name,
                  "organization" => $value->organization,
                  "date" => $value->date,
                  "speсialization" => $value->speсialization,
               ]);

               if($educationCreate) {
                  SpecialistEducation::create([
                     'id_specialist' => $request->id,
                     'id_education' => $educationCreate->id,
                  ]);   
               }
   
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value->id, 
                  // Новый id
                  'new' => $educationCreate->id
               ];            
               continue;
            };       

            // Обновление
            $education = Education::find($value->id);
            $educationUpdate = $education->update([
               "name" => $value->name,
               "organization" => $value->organization,
               "date" => $value->date,
               "speсialization" => $value->speсialization,
            ]);      
            
            if(!$educationUpdate) {
               return [
                  "object" => (object) [
                     "success" => false,
                     "debug" => true,
                     "message" => "Не удалось обновить значение.",
                     "result" => null,
                  ],
                  "status" => 500,
               ];      
            }
         }

         return [
            "object" => (object) [
               "success" => true,
               "debug" => false,
               "message" => "Данные об образованиях сохранились.",
               "result" => $arrayID,
            ],
            "status" => 200,
         ];
      } else {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Специалист не найден.",
               "result" => null,
            ],
            "status" => 500,
         ];      
      }     
   }
   
   /* Модуль: Сохранение прошлых работ */
   public function saveSpecialistWorks(Request $request) {
      $works = json_decode($request->works);
      $id = json_decode($request->id);

      $specialist = Specialist::find($id);
      if($specialist) {         
         $arrayID = [];

         foreach ($works as $key => $value) {
            // Удаление
            if ($value->delete === true){
               $work = Work::find($value->id);
               $work->delete();
               continue;
            }         

            // Создание
            if ($value->create === true) {
               $workCreate = Work::create([
                  "name" => $value->name,
                  "organization" => $value->organization,
                  "startWork" => $value->startWork,
                  "endWork" => $value->endWork,
               ]);

               if($workCreate) {
                  SpecialistWork::create([
                     'id_specialist' => $request->id,
                     'id_work' => $workCreate->id,
                  ]);   
               }
   
               $arrayID[] = (object) [
                  // Прошлый id
                  'old' => $value->id, 
                  // Новый id
                  'new' => $workCreate->id
               ];            
               continue;
            };       

            // Обновление
            $work = Work::find($value->id);
            $workUpdate = $work->update([
               "name" => $value->name,
               "organization" => $value->organization,
               "startWork" => $value->startWork,
               "endWork" => $value->endWork,
            ]);           
         
            if(!$workUpdate) {
               return [
                  "object" => (object) [
                     "success" => false,
                     "debug" => true,
                     "message" => "Не удалось обновить значение.",
                     "result" => null,
                  ],
                  "status" => 500,
               ];      
            }
         }

         return [
            "object" => (object) [
               "success" => true,
               "debug" => false,
               "message" => "Данные о прошлых работах сохранились.",
               "result" => $arrayID,
            ],
            "status" => 200,
         ];      
      } else {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Специалист не найден.",
               "result" => null,
            ],
            "status" => 500,
         ];      
      };
   }
   
   /* Сохранение таблицы со специалистами */ 
   public function saveSpecialistsChanges(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'specialists' => 'nullable|array',
      ], [
         'specialists.array' => ':attribute не массив.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

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

      $specialists = Specialist::all();
      
      // Получение всех файлов
      $filesSpecialists = Storage::files('public/specialists');
      
      if($filesSpecialists) {
         foreach ($filesSpecialists as $fileKey => $fileValue) {
            $useFile = false;
            /* Проверка на использование файла */
            foreach ($specialists as $specialistKey => $specialistValue) {
               /* Обрезание значения $fileValue до названия файла */
               $str = str_replace('public/specialists/', '', $fileValue);
               /* Проверка значения названия файла на совпадение */
               if ($specialistValue->filename == $str) {
                  $useFile = true;
               };
            };
   
            /* Удаление файла, если не используется */
            if (!$useFile) {
               Storage::delete($fileValue);
            };
         };
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "result" => null,
      ], 200);
   }

   /* Добавление нового специалиста */ 
   public function addSpecialist(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'image' => [
            'required',
            File::image()
               ->types(['png', 'webp'])
               ->max(5 * 1024)
               ->dimensions(Rule::dimensions()->maxWidth(1500)->maxHeight(1500)),
            ],
      ], [
         'image.required' => 'Файл не загружен.',
         'image.image' => 'Допустимые типы файлов: png, webp.',
         'image.max' => 'Максимальный размер 5MB.',
         'image.dimensions' => 'Максимальное разрешение 1500x1500px.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      // Получение пути нового файла
      $path = $request->file('image')->store(
         'public/specialists'
      );

      if (!$path) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось сохранить изображение.",
            "result" => null,
         ], 500);
      };
      
      $profile = json_decode($request->profile);

      try {
         $specialist = Specialist::create([
            "link" => $profile->link->value,
            "family" => $profile->family->value,
            "name" => $profile->name->value,
            "surname" => $profile->surname->value,
            "description" => $profile->description->value,
            "category" => $profile->category->value,
            "degree" => $profile->degree->value,
            "rank" => $profile->rank->value,
            "startWorkAge" => $profile->startWorkAge->value ? $profile->startWorkAge->value : null,
            "startWorkCity" => $profile->startWorkCity->value,
            "adultDoctor" => $profile->adultDoctor->value,
            "childrenDoctor" => $profile->childrenDoctor->value,
            "childrenDoctorAge" => $profile->childrenDoctor->value ? $profile->childrenDoctorAge->value : 0,
            "filename" => str_replace("public/specialists/", "", $path),
         ]);   
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось создать специалиста.",
            "result" => null,
         ], 500);   
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Специалист создан.",
         "result" => [
            "id" => $specialist->id,
            "path" => Storage::url($path),
         ],
      ], 200);   
   }

   /* Сохранение изменений специализаций */
   public function saveSpecializationsChanges(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'specializations' => 'nullable|array',
      ], [
         'array' => 'Поле :attribute должно быть массивом.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

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
      
      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "result" => $arrayID
      ], 200);
   }

   /* Сохранение изменений клиник */
   public function saveClinicsChanges(Request $request) {
      // Валидация
      $validator = Validator::make($request->all(), [
         'clinics' => 'nullable|array',
      ], [
         'array' => 'Поле :attribute должно быть массивом.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $arrayID = [];

      // Поиск или добавление
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

      return response()->json([
         "success" => true,
         "debug" => true,
         "errors" => $validator->errors(),
         "message" => "Клиники сохранены.",
         "result" => $arrayID,
      ], 200);
   }

   /* Создание XML */
   public function makeSpecialistsXML(Request $request) {
      $validator = Validator::make($request->all(), [
         'name' => 'required',
         'company' => 'required',
         'url' => 'required',
         'email' => 'required',
         'description' => 'required',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ]);
      };

      // Получение данных из бд
      $specialists = Specialist::all();
      $specializations = Specialization::all();
      $clinics = Clinic::all();

      $specializationNames = $specializations->pluck('name', 'id')->all();
      $specializationsBySpecialists = SpecialistSpecialization::all()->groupBy("id_specialist");

      $clinicsById = $clinics->groupBy("id")->all();
      $clinicsBySpecialists = SpecialistClinic::all()->where("priem", true)->groupBy("id_specialist");

      $xw = new XMLWriter();
      $xw->openMemory();
      
      // Начало документа
      $xw->startDocument("1.0");
   
      // Запись элементов
      $xw->startElement("yml_catalog");
      
      $xw->startAttribute("date");
      $xw->text(date('Y-m-d H:i'));    
      $xw->endAttribute();
         $xw->startElement("shop");
            // Название клиники
            $xw->startElement("name");
            $xw->text($request->name);
            $xw->endElement();

            // Компания 
            $xw->startElement("company");
            $xw->text($request->company);
            $xw->endElement();

            // Ссылка на сайт 
            $xw->startElement("url");
            $xw->text($request->url);
            $xw->endElement();

            // Рабочая почта 
            $xw->startElement("email");
            $xw->text($request->email);
            $xw->endElement();

            // Картинка с логотипом 
            $xw->startElement("picture");
            $xw->text($request->name . '/storage/img/logo.webp');
            $xw->endElement();

            // Описание 
            $xw->startElement("description");
            $xw->text($request->description);
            $xw->endElement();

            // Валюты 
            $xw->startElement("currencies");
               $xw->startElement("currency");
               
               $xw->startAttribute("id");
               $xw->text("RUB");
               $xw->endAttribute();               

               $xw->startAttribute("rate");
               $xw->text("1");
               $xw->endAttribute();               

               $xw->endElement();
            $xw->endElement();

            // Категории 
            $xw->startElement("categories");
               $xw->startElement("category");
               
               $xw->startAttribute("id");
               $xw->text("1");
               $xw->endAttribute();               

               $xw->text("Врач");

               $xw->endElement();
            $xw->endElement();

            // Специализации
            $xw->startElement("sets");
               foreach ($specializations as $key => $value) {
                  $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($value->name);
                  $stringLowerCase = strtolower($stringTransliterate);
                  
                  $xw->startElement("set");                  
                     $xw->startAttribute("id");
                     $xw->text($stringLowerCase);
                     $xw->endAttribute();         

                     // Название
                     $xw->startElement("name");
                     $xw->text($value->name);
                     $xw->endElement();
   
                     // Ссылка на раздел с врачами
                     $xw->startElement("url");
                     $xw->text($request->url . '/specialists');
                     $xw->endElement();
   
                  $xw->endElement();
               }
            $xw->endElement();

            // Офферы (специлисты)
            $xw->startElement("offers");
               foreach ($specialists as $key => $value) {
                  $xw->startElement("offer");
                     $xw->startAttribute("id");
                     $xw->text('vrach' . $value->id);
                     $xw->endAttribute();

                        $xw->startElement("name");
                        $xw->text($value->family . ' ' . $value->name . ' ' . $value->surname);
                        $xw->endElement();                  

                        $xw->startElement("url");
                        $xw->text($request->url . '/' . $this->makeUrl($value->family . ' ' . $value->name . ' ' . $value->surname));
                        $xw->endElement();                  

                        // Цена на услугу
                        $xw->startElement("price");
                           $xw->startAttribute("from");
                           $xw->text('true');
                           $xw->endAttribute();         
                        $xw->text(0);
                        $xw->endElement();

                        // Название услуги
                        $xw->startElement("sales_notes");
                        $xw->text('Первичный прием');
                        $xw->endElement();   

                        // Специальности через запятую
                        $xw->startElement("set-ids");
                           $stringSpecializations = '';
                           $array = $specializationsBySpecialists[$value->id] ?? [];

                           foreach ($array as $innerKey => $innerValue) {
                              $stringSpecializations .= $this->formatStringTransliterate($specializationNames[$innerValue->id_specialization], 'lower') . ',';
                           };
                        $xw->text(rtrim($stringSpecializations, ','));
                        $xw->endElement();

                        // Фото специалиста
                        $xw->startElement("picture");
                        $xw->text($request->url . Storage::url('public/specialists/' . $value->filename));
                        $xw->endElement();

                        // Описание
                        $xw->startElement("description");
                        $xw->text($value->description);
                        $xw->endElement();

                        // id категории из списка категорий
                        $xw->startElement("categoryId");
                        $xw->text(1);
                        $xw->endElement();

                        // Фамилия
                        $xw->startElement("param");
                           $xw->startAttribute("name");
                           $xw->text('Фамилия');
                           $xw->endAttribute();                                 
                        $xw->text($value->family);
                        $xw->endElement();

                        // Фамилия
                        $xw->startElement("param");
                           $xw->startAttribute("name");
                           $xw->text('Имя');
                           $xw->endAttribute();                                 
                        $xw->text($value->name);
                        $xw->endElement();

                        // Отчество
                        if ($value->surname) {
                           $xw->startElement("param");
                              $xw->startAttribute("name");
                              $xw->text('Отчество');
                              $xw->endAttribute();                                 
                           $xw->text($value->surname);
                           $xw->endElement();
                        };

                        // Годы опыта
                        $xw->startElement("param");
                           $xw->startAttribute("name");
                           $xw->text('Годы опыта');
                           $xw->endAttribute();                                 
                        $xw->text(round(Carbon::parse($value->startWorkAge)->diffInYears(Carbon::parse(date('Y-m-d')))));
                        $xw->endElement();

                        // Город, в котором врач ведет прием.
                        if ($value->startWorkCity == null or $value->startWorkCity == '') {
                           return response()->json([
                              "status" => false,
                              "message" => "Город ведения приема у " . $value->family . " " . $value->name . " " . $value->surname . " не указан.",
                              "data" => null,
                           ]);
                        };

                        $xw->startElement("param");
                           $xw->startAttribute("name");
                           $xw->text('Город');
                           $xw->endAttribute();                                 
                        $xw->text($value->startWorkCity);
                        $xw->endElement();

                        // Прием взрослых (от 18 лет). Возможные значения: true или false (по умолчанию).
                        $xw->startElement("param");
                           $xw->startAttribute("name");
                           $xw->text('Взрослый врач');
                           $xw->endAttribute();                                 
                        $xw->text($value->adultDoctor == 1 ? 'true' : 'false');
                        $xw->endElement();

                        // Прием детей (до 18 лет). Возможные значения: true или false (по умолчанию).
                        $xw->startElement("param");
                           $xw->startAttribute("name");
                           $xw->text('Детский врач');
                           $xw->endAttribute();                                 
                        $xw->text((bool)$value->childrenDoctor == 1 ? 'true' : 'false');
                        $xw->endElement();

                        // Город клиники приема и адрес
                        $innerClinics = $clinicsBySpecialists[$value->id] ?? [];
                           
                        if (count($innerClinics) == 0) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Клиника ведения приема у " . $value->family . " " . $value->name . " " . $value->surname . " не указана.",
                              "result" => null,
                           ]);
                        }

                        foreach ($clinicsBySpecialists[$value->id] ?? [] as $innerKey => $innerValue) {
                           $xw->startElement("param");
                              $xw->startAttribute("name");
                              $xw->text('Город клиники');
                              $xw->endAttribute();                                 
                           $xw->text('г. ' . $clinicsById[$innerValue->id_clinic][0]->city);
                           $xw->endElement();

                           $xw->startElement("param");
                              $xw->startAttribute("name");
                              $xw->text('Адрес клиники');
                              $xw->endAttribute();                                 
                           $xw->text('ул. ' . $clinicsById[$innerValue->id_clinic][0]->street . ', д. ' . $clinicsById[$innerValue->id_clinic][0]->home);
                           $xw->endElement();

                           $xw->startElement("param");
                              $xw->startAttribute("name");
                              $xw->text('Название клиники');
                              $xw->endAttribute();                                 
                           $xw->text($clinicsById[$innerValue->id_clinic][0]->name);
                           $xw->endElement();

                           break;
                        };
                  $xw->endElement();                  
               }
            $xw->endElement();

         $xw->endElement();
      $xw->endElement();

      // Конец документа
      $xw->endDocument();

      // Проверка наличия директории
      if (!Storage::exists('public/xml')) {
         Storage::makeDirectory('public/xml');
      };

      if (!Storage::put('public/xml/doctors.xml', $xw->outputMemory())) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось записать файл.",
            "result" => null,
         ]);
      } else {
         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "XML-файл создан.",
            "result" => null,
         ]);
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     О НАС                         |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Информационные блоки                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function saveAboutsChanges(Request $request) {
      $abouts = json_decode($request->abouts);
      $arrayID = [];

      foreach ($abouts as $key => $value) {
         // Удаление
         if ($value->delete === true) {
            $about = About::find($value->id);
            $about->delete();
            continue;
         };

         // Добавление
         if ($value->create === true){
            $aboutCreate = About::create([
               "order" => $value->order,
               "title" => $value->title,
               "description" => $value->description,
               "imageOne" => $value->imageOne,
               "imageTwo" => $value->imageTwo,
               "imageThree" => $value->imageThree,
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value->id, 
               // Новый id
               'new' => $aboutCreate->id
            ];
            continue;
         };

         // Обновление
         $about = About::find($value->id);
         $about->update([
            "order" => $value->order,
            "title" => $value->title,
            "description" => $value->description,
            "imageOne" => $value->imageOne,
            "imageTwo" => $value->imageTwo,
            "imageThree" => $value->imageThree,
         ]);   
      };

      // Сортировка слайдов по порядку от наименьшего до наибольшого
      $aboutsAll = About::all()->SortBy('order');

      // Обновление порядковых номеров
      $count = 0;
      foreach ($aboutsAll as $aboutKey => $aboutValue) {
         $count++;
         $aboutValue->order = $count;
         $aboutValue->save();
      };

      // Получение всех файлов
      $filesAbouts = Storage::files('public/abouts');
      if($filesAbouts) {
         foreach ($filesAbouts as $fileKey => $fileValue) {
            $useFile = false;
            /* Проверка на использование файла */
            foreach ($aboutsAll as $aboutKey => $aboutValue) {
               /* Обрезание значения $fileValue до названия файла */
               $str = str_replace('public/abouts/', '', $fileValue);
               /* Проверка значения названия файла на совпадение */
               if (
                  $aboutValue->imageOne == $str || 
                  $aboutValue->imageTwo == $str || 
                  $aboutValue->imageThree == $str
               ) {
                  $useFile = true;
               };
            };
   
            /* Удаление файла, если не используется */
            if (!$useFile) {
               Storage::delete($fileValue);
            };
         };
      };

      return response()->json([
         "status" => true,
         "message" => "Данные обновлены.",
         "data" => $arrayID,
      ]);
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    КОНТАКТЫ                       |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Информационные блоки                              */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function saveContactsChanges(Request $request) {
      $contacts = json_decode($request->contacts);
      $arrayID = [];

      foreach ($contacts as $key => $value) {
         // Удаление
         if ($value->delete === true) {
            $contact = Contact::find($value->id);
            $contact->delete();
            continue;
         };
         // Добавление
         if ($value->create === true){
            $contactCreate = Contact::create([
               "name" => $value->name,
               "order" => $value->order,
               "clinicId" => $value->clinicId ? $value->clinicId : null,
            ]);
               
            // Телефоны
            $phones = ContactPhone::where('contactId', $value->id)->get();
            foreach ($phones as $phoneKey => $phoneValue) {
               $phone = Phone::find($phoneValue->phoneId);
               $phone->delete();
            };

            foreach ($value->phones as $key => $valuePhone) {
               $phoneCreate = Phone::create([
                  "name" => $valuePhone->name,
               ]);
   
               ContactPhone::create([
                  'contactId' => $contactCreate->id,
                  'phoneId' => $phoneCreate->id,
               ]);
            }

            // Почты
            $emails = ContactMail::where("contactId", $value->id)->get();
            foreach ($emails as $emailKey => $emailValue) {
               $email = Mail::find($emailValue->mailId);
               $email->delete();
            };

            foreach ($value->mails as $key => $valueEmail) {
               $emailCreate = Mail::create([
                  "name" => $valueEmail->name,
               ]);
   
               ContactMail::create([
                  "contactId" => $contactCreate->id,
                  "mailId" => $emailCreate->id,
               ]);
            }
            
            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               "old" => $value->id, 
               // Новый id
               "new" => $contactCreate->id
            ];
            continue;
         };

         // Обновление
         $contact = Contact::find($value->id);
         $contact->update([
            "name" => $value->name,
            "order" => $value->order,
            "clinicId" => ($value->clinicId == "null" || $value->clinicId == null) ? null : $value->clinicId,
         ]);   

         // Телефоны
         $phones = ContactPhone::where("contactId", $value->id)->get();
         foreach ($phones as $phoneKey => $phoneValue) {
            $phone = Phone::find($phoneValue->phoneId);
            $phone->delete();
         };

         foreach ($value->phones as $key => $valuePhone) {
            $phoneCreate = Phone::create([
               "name" => $valuePhone->name,
            ]);

            ContactPhone::create([
               "contactId" => $contact->id,
               "phoneId" => $phoneCreate->id,
            ]);
         }

         // Почты
         $emails = ContactMail::where('contactId', $value->id)->get();
         foreach ($emails as $emailKey => $emailValue) {
            $email = Mail::find($emailValue->mailId);
            $email->delete();
         };

         foreach ($value->mails as $key => $valueEmail) {
            $emailCreate = Mail::create([
               "name" => $valueEmail->name,
            ]);

            ContactMail::create([
               'contactId' => $contact->id,
               'mailId' => $emailCreate->id,
            ]);
         }
      };

      // Сортировка слайдов по порядку от наименьшего до наибольшого
      $contactAll = Contact::all()->SortBy('order');

      // Обновление порядковых номеров
      $count = 0;
      foreach ($contactAll as $contactKey => $contactValue) {
         $count++;
         $contactValue->order = $count;
         $contactValue->save();
      };

      return response()->json([
         "status" => true,
         "message" => "Данные обновлены.",
         "data" => $arrayID,
      ]);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                   РАСПИСАНИЕ                      |*/
   /* |___________________________________________________|*/
   /* Сохранение расписания */
   public function saveShedulesAll($isManual = null) {
      // Получение всех файлов
      $files = Storage::disk('private')->files('schedules');

      // Сортировка массива файлов
      $jsonFiles = array_filter($files, function($file) {
         return pathinfo($file, PATHINFO_EXTENSION) === 'json';
      });
      
      // Поиск самого актуального файла
      $lastFile = null;
      foreach ($jsonFiles as $key => $value) {
         if ($lastFile === null) {
            $lastFile = $value;
            continue;
         }; 

         $CurrentFileName = pathinfo($value, PATHINFO_FILENAME);
         $dateLastFileName = pathinfo($lastFile, PATHINFO_FILENAME);
         
         try {            
            $currentFileDate = Carbon::createFromFormat('Y-m-d H-i', $CurrentFileName);
            $lastFileDate = Carbon::createFromFormat('Y-m-d H-i', $dateLastFileName);
         } catch (Exception $e) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => $e->getMessage(),
                  "result" => null
               ], 500);
            }

            Log::error($e->getMessage());
            continue;
         }

         if ($currentFileDate > $lastFileDate) {
            $lastFile = $value;
            continue;
         };
      }

      // Если файл есть
      if ($lastFile) {
         $file = Storage::disk('private')->get($lastFile);
         $fileContent = json_decode($file);
         
         $clinics = $fileContent?->clinics;
         if (!$clinics) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => 'Отсутствуют клиники.',
                  "result" => null
               ], 500);
            }

            Log::error('Отсутствуют клиники.');
         }

         $week = $fileContent?->week;
         if (!$week) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => 'Отсутствуют дни.',
                  "result" => null
               ], 500);
            }

            Log::error('Отсутствуют дни.');
         }

         $shedules = $fileContent?->shedules;
         if (!$shedules) {
            if ($isManual) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => 'Отсутствует расписание.',
                  "result" => null
               ], 500);
            }

            Log::error('Отсутствует расписание.');
         }
      } else {
         if ($isManual) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => 'Отсутствует файл.',
               "result" => null
            ], 500);
         }

         Log::error('Отсутствует файл.');
      };
      
      // Сбрасываю ограничения внешнего ключа, чтобы очистить таблицы
      DB::statement('SET FOREIGN_KEY_CHECKS = 0');

      // Очищаю таблицы
      Shedule::truncate();
      ShedulesClinic::truncate();
      ShedulesCurrentDay::truncate();
      ShedulesDay::truncate();
      ShedulesDaysTime::truncate();

      // Возвращаю ограничения внешнего ключа 
      DB::statement('SET FOREIGN_KEY_CHECKS = 1');

      try {
         $clinicId = [];
         foreach ($clinics as $clinicsKey => $clinicsValue) {
            $clinic = ShedulesClinic::create([
               "name" => $clinicsValue->name,
            ]);
   
            $clinicId['' . $clinicsValue->id . ''] = $clinic->id;
         };
   
         $currentDays = [];
         foreach ($week as $weekKey => $weekValue) {
            $day = ShedulesCurrentDay::create([
               "date" => $weekValue->date,
            ]);
   
            $currentDays[] = (object) [
               "id" => $day->id,
               "date" => $weekValue->date,
            ];
         };
   
         foreach ($shedules as $shedulesKey => $shedulesValue) {
            $shedule = Shedule::create([
               "name" => $shedulesValue->name,
               "specializations" => $shedulesValue->specializations,
            ]);
   
            foreach ($shedulesValue->weeks as $sheduleWeekKey => $sheduleWeekValue) {
               if ($sheduleWeekValue->status === true) {
                  foreach ($sheduleWeekValue->content as $sheduleWeekContentKey => $sheduleWeekContentValue) {
                     $day = ShedulesDay::create([
                        "date" => $sheduleWeekContentValue->date,
                        "sheduleId" => $shedule->id,
                        "clinicId" => $clinicId[$sheduleWeekValue->clinicId],
                     ]);
   
                     foreach ($sheduleWeekContentValue->time as $ContentValueTimeKey => $ContentValueTimeValue) {
                        $time = ShedulesDaysTime::create([
                           "name" => $ContentValueTimeValue,
                           "dayId" => $day->id,
                        ]);
                     };
                  };
               };
            };
         };

         if ($isManual) {
            return response()->json([
               "success" => true,
               "debug" => true,
               "message" => 'Данные обновлены.',
               "result" => null
            ], 200);
         }
      } catch (Throwable $e) {
         if ($isManual) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => $e,
               "result" => null
            ], 500);
         }

         Log::error($e);
      }   
   }
   
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      ЦЕНЫ                         |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Файлы с ценами                                    */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Получение всех файлов с ценами */
   public function getPricesFilesAll(Request $request) {
      $pricesFiles = PriceFile::all();
      
      if ($pricesFiles->isEmpty()) {
         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Цен нет.",
            "result" => null,
         ]);
      } else {
         foreach ($pricesFiles as $pricesFilesKey => $pricesFilesValue) {
            $pricesFiles[$pricesFilesKey]->path = Storage::url('prices/' . $pricesFilesValue->filename);
            $pricesFiles[$pricesFilesKey]->date = Carbon::parse($pricesFilesValue->created_at)->format('d.m.Y, H:i:s');
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $pricesFiles,
         ]);   
      };
   }

   /* Сохранение изменений */
   public function savePricesChanges(Request $request) {   
      $generateCategories = [];
      $pricesFiles = json_decode($request->pricesFiles);
      $arrayID = [];

      foreach ($pricesFiles as $key => $value) {
         // Удаление
         if ($value->delete === true){
            $priceFile = PriceFile::find($value->id);
            $priceFile->delete();
            continue;                  
         }         

         // Создание
         if ($value->create === true) {
            $priceFileCreate = PriceFile::create([
               "filename" => $value->filename,
            ]);

            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value->id, 
               // Новый id
               'new' => $priceFileCreate->id
            ];            
            continue;
         };       
      };

      $priceFiles = PriceFile::all();
      // Получение всех файлов
      $filesPrices = Storage::files('public/prices');
      if($filesPrices) {
         foreach ($filesPrices as $fileKey => $fileValue) {
            $useFile = false;
            // Проверка на использование файла
            foreach ($priceFiles as $priceFilesKey => $priceFilesValue) {
               // Обрезание значения $fileValue до названия файла
               $str = str_replace('public/prices/', '', $fileValue);
               // Проверка значения названия файла на совпадение
               if ($priceFilesValue->filename == $str) {
                  $useFile = true;
               };
            };
   
            if (!$useFile) {
               Storage::delete($fileValue);
            };
         };
      };

      if (count($pricesFiles) > 0) {
         $filesPrices = Storage::files('public/prices');

         // Очищение таблиц
         DB::statement('SET FOREIGN_KEY_CHECKS = 0');

         PriceValue::truncate();
         PriceCategory::truncate();
         PriceAddress::truncate();
         
         DB::statement('SET FOREIGN_KEY_CHECKS = 1');

         // Считывание данных с файлов в дериктории
         foreach ($filesPrices as $filesPricesKey => $filesPricesValue) {
            // Получение массивов
            $dataFromFile = $this->getDataFromFile(Storage::path($filesPricesValue));

            if (!$dataFromFile['object']->success) {
               return response()->json($dataFromFile['object'], $dataFromFile['status']);
            }; 

            // Перебор полученных массивов            
            foreach ($dataFromFile['object']->result as $dataFromFileKey => $dataFromFileValue) { 
               $index = 0;
               $currentAddress = null;
               $categorys = [];
               $currentCategory = null;
               
               foreach ($dataFromFileValue as $dataKey => $dataValue) {
                  // Заполнение адреса 
                  if (($index + 1) === 12) {
                     // Проверка на пустой адресс
                     if (empty($dataValue[0])) {
                        return response()->json([
                           "success" => false,
                           "debug" => true,
                           "message" => "Название адреса в файле " . basename(Storage::path($filesPricesValue)) . " не может быть пустым.",
                           "result" => $arrayID,
                        ], 422);
                     };
                     
                     $currentAddress = PriceAddress::create([
                        "name" => $dataValue[0],
                     ]);
                  }
                  
                  // Заполнение данных из таблицы 
                  if (($index + 1) >= 16) {
                     if (count($dataValue) < 2) { 
                        return response()->json([
                           "success" => false,
                           "debug" => true,
                           "message" => "Недостаточно данных (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                           "result" => $arrayID,
                        ], 422);
                     };

                     $levelClear = trim($dataValue[0], " ");                        

                     // Проверка на категорию
                     if (str_contains($levelClear, '#')) {
                        if (empty($dataValue[1])) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Название категории (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . " не может быть пустым.",
                              "result" => $arrayID,
                           ], 422);
                        };

                        // Проверка на пустую общую категорию
                        if (strlen($levelClear) > 1 && $currentCategory === null) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Нельзя создать подкатегорию без общей категории (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "result" => $arrayID,
                           ], 422);
                        };
                        
                        // Создание новой категории
                        $currentCategory = PriceCategory::create([
                           "name" => $dataValue[1],
                           "addressId" => $currentAddress->id,
                           "categoryId" => $this->getMainCategory($categorys, trim($levelClear)),
                        ]);

                        // Заполнение массива с категориями
                        $categorys[] = (object) [
                           "id" => $currentCategory->id,
                           "level" => $levelClear,
                        ];                                            
                     };
                     
                     if (is_numeric($levelClear)) {
                        if (count($dataValue) < 3) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Недостаточно данных (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "result" => $arrayID,
                           ], 422);
                        };

                        if (!is_numeric($dataValue[2])) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Цена (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . " должна быть числом.",
                              "result" => $arrayID,
                           ], 422);
                        };

                        $createPriceValue = PriceValue::create([
                           "name" => $dataValue[1],
                           "price" => $dataValue[2],
                           "categoryId" => $currentCategory->id,
                        ]);

                        if (!$createPriceValue) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Не удалось создать цену (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "result" => $arrayID,
                           ], 422);
                        };
                     };
                  };

                  $index++;
               };
            };
         };   
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Цены успешно сохранены.",
         "result" => count($arrayID) > 0 ? $arrayID : null,
      ], 200);            
   }

   /* Считывание данных с файла .ods, .xls, .xlsx */
   protected function getDataFromFile($path) {
      try {
         // Существует ли файл
         if (!file_exists($path)) {
            return [
               "object" => (object) [
                  "success" => false,
                  "debug" => true,
                  "message" => "Файл '" . basename($path) . "' не существует.",
                  "result" => null,
               ],
               "status" => 500,
            ];
         }

         // Загрузка файла
         $spreadsheet = IOFactory::load($path);         
         // Получение всех листов файла
         $sheetsAll = $spreadsheet->getAllSheets();
         // Данные всех листов
         $sheetsAlldata = [];
         
         foreach ($sheetsAll as $sheet) {         
            if ($this->isExcelEmpty($sheet)) {
               return [
                  "object" => (object) [
                     "success" => false,
                     "debug" => true,
                     "message" => "Раздел '" . $sheet->getTitle() . "' в файле '" . basename($path) . "' пустой.",
                     "result" => null,
                  ],
                  "status" => 500,
               ];
            };

            // Получение названия листа
            $sheetName = $sheet->getTitle();

            // Получение данных из листа
            $sheetData = [];

            // Получение всех данных при помощи итератора
            $rowIterator = $sheet->getRowIterator();
            
            foreach ($sheet->getRowIterator() as $row) {
               // Получение итератора ячейки 
               $cellIterator = $row->getCellIterator();
      
               // Установите это значение в TRUE, если хотите пропускать пустые ячейки
               $cellIterator->setIterateOnlyExistingCells(TRUE); 
               
               $rowData = [];
               foreach ($cellIterator as $cell) {
                  $rowData[] = $cell->getValue();
               }

               $sheetData[] = $rowData;
            }
            
            $sheetsAlldata[] = $sheetData;
         };

         return [
            "object" => (object) [
               "success" => true,
               "debug" => false,
               "message" => "Данные с файла считаны.",
               "result" => $sheetsAlldata,
            ],
            "status" => 200,
         ];
      } catch (Exception $e) {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Не удалось считать данные.",
               "result" => null,
            ],
            "status" => 500,
         ];
      };
   }

   /* Проверка на пустой лист */
   function isExcelEmpty($sheet) {
      $highestRow = $sheet->getHighestRow();
      $highestColumn = $sheet->getHighestColumn();

      for ($row = 1; $row <= $highestRow; $row++) {
         for ($col = 'A'; $col <= $highestColumn; $col++) {
               $cellValue = $sheet->getCell($col . $row)->getValue();
               if (!empty(trim($cellValue))) {
                  return false;
               }
         }
      }

      return true; 
   }

   /* Поиск общей категории */
   function getMainCategory($categories, $categorylevel) {
      $lentghCurrentCategory = strlen($categorylevel);
      if ($lentghCurrentCategory === 1) {
         return null;
      };

      // Поиск общей категории
      for ($i = count($categories) - 1; $i >= 0; $i--) {
         $lentghCategory = strlen(trim($categories[$i]->level, " "));
         
         if ($lentghCategory < $lentghCurrentCategory) {
            return $categories[$i]->id;         
            continue;
         };
      };

      return $categories[count($categories) - 1]->id;
   }

   /* Получение файла и удаление */
   public function downloadPricesArchive() {
      // Получаем список файлов без полных путей
      $files = Storage::disk('public')->files('reports');
      
      if (empty($files)) {
         return response()->json(['message' => 'No files to download'], 404);
      }

      $zip = new ZipArchive;
      $zipFileName = 'prices' . time() . '.zip';
      $zipPath = storage_path('app/public/' . $zipFileName);

      if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
         foreach ($files as $file) {
            // Получаем только имя файла без директории
            $fileName = basename($file);

            // Добавляем файл в архив с указанием правильного пути
            $zip->addFile(storage_path('app/public/' . $file), $fileName);
         }

         $zip->close();
      } else {
         return response()->json(['message' => 'Failed to create zip archive'], 500);
      }

      return response()->download($zipPath)->deleteFileAfterSend(true);
   }

   /* Создание файлов с ценами */   
   public function makePricesFiles(Request $request) {
      try {
         // Проверка наличия директории
         if (!Storage::exists('public/reports')) {
            Storage::makeDirectory('public/reports');
         };

         // Удаление старых файлов
         $files = Storage::disk('public')->allFiles('reports');
         Storage::disk('public')->delete($files);

         $fields = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
         ];

         $fieldsTitles = [
            1 => 'Категория',
            2 => 'Название',
            3 => 'Цена',
         ];

         $addressesAll = PriceAddress::all();

         if (count($addressesAll) === 0) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Список адресов пуст.",
               "result" => null,
            ], 500);
         };

         $categoriesAll = PriceCategory::all()->groupBy('addressId');

         if (count($categoriesAll) === 0) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Список категорий пуст.",
               "result" => null,
            ], 500);
         };

         $test = [];

         foreach ($addressesAll as $key => $value) {
            // Создание нового табличного документа
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Установка ширины столбцов
            foreach ($fields as $fieldsKey => $fieldsValue) {
               $sheet->getColumnDimension($fieldsValue)->setAutoSize(true);
            }

            // Заполнение заголовков
            foreach ($fieldsTitles as $fieldsTitlesKey => $fieldsTitlesValue) {
               $sheet->setCellValue($fields[$fieldsTitlesKey] . '1', $fieldsTitlesValue);
            };

            // Получение всех категорий по адресу
            $categories = $categoriesAll[$value->id]->groupBy('id');

            // Получение всех цен
            $prices = PriceValue::all()->whereIn(
               'categoryId', 
               $categories->keys()->toArray()
            );
            
            // Заполнение данных
            $count = 2;
            foreach ($prices as $priceKey => $priceValue) {
               $sheet->setCellValue($fields[1] . ($count), $categories[$priceValue->categoryId][0]->name);
               $sheet->setCellValue($fields[2] . ($count), $priceValue->name);
               $sheet->setCellValue($fields[3] . ($count), $priceValue->price);

               $count++;
            };

            // Сохранение файла в папке storage
            $fileName = $value->name . '.xlsx';
            $files[] = $fileName;

            $writer = new Xlsx($spreadsheet);
            $filePath = storage_path('app/public/reports/' . $fileName);
            $writer->save($filePath);
         }
         
         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => 'Файл успешно создан.',
            "result" => $files,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось создать файл.",
            "result" => null,
         ], 500);
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    НОВОСТИ                        |*/
   /* |___________________________________________________|*/
   /* Добавление новости */
   public function addNews(Request $request) {
      $validator = Validator::make($request->all(), [
         'image' => [
            'required',
            File::image()
               ->types(['png', 'webp', 'jpg', 'jpeg'])
               ->max(5 * 1024)
               ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
         ],
         'title' => 'required|string',
         'description' => 'required|string',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'string' => 'Поле :attribute должно быть строкой.',
         'image.types' => 'Допустимые типы файлов: png, webp, jpg, jpeg.',
         'image.max' => 'Максимальный размер 5MB.',
         'image.dimensions' => 'Максимальное разрешение 2000x2000px.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $path = request()->file('image')->store(
         'public/news'
      );

      if (!$path) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось сохранить изображение.",
            "result" => null,
         ], 500);
      };

      try {
         $news = News::create([
            'image' => basename($path),
            'title' => $request->title,
            'description' => $request->description,
         ]);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось добавить новость.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Новость добавлена.",
         "result" => [
            "id" => $news->id,
            "path" => Storage::url($path),
            "image" => basename($path),
            "title" => $request->title,
            "description" => $request->description,
            "url_date" => Carbon::parse($news->created_at)->format('d.m.Y'),
            "url_time" => Carbon::parse($news->created_at)->format('H:i:s'),
         ],
      ], 200);
   }

   /* Изменение новости */
   public function saveNewsChangesOnce(Request $request) {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('news', 'id'),
         ],
         'title' => 'required|string',
         'description' => 'required|string',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'string' => 'Поле :attribute должно быть строкой.',
         'exists' => 'Такой новости не существует (:attribute).',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $path = null;
      
      if ($request->hasFile('image')) {
         $validatorImage = Validator::make($request->all(), [
            'image' => [
               'required',
               File::image()
                  ->types(['png', 'webp', 'jpg', 'jpeg'])
                  ->max(5 * 1024)
                  ->dimensions(Rule::dimensions()->maxWidth(2000)->maxHeight(2000)),
            ],
         ], [
            'image.required' => 'Файл не загружен.',
            'image.types' => 'Допустимые типы файлов: png, webp, jpg, jpeg.',
            'image.max' => 'Максимальный размер 5MB.',
            'image.dimensions' => 'Максимальное разрешение 2000x2000px.',
         ]);

         if ($validatorImage->fails()) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "errors" => $validatorImage->errors(),
               "message" => "Некорректные данные.",
               "result" => null,
            ], 422);
         };

         $path = request()->file('image')->store(
            'public/news'
         );

         if (!$path) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "errors" => $validator->errors(),
               "message" => "Не удалось сохранить изображение.",
               "result" => null,
            ], 500);
         };
      };

      $news = News::find($request->id);

      try {
         $slideUpdate = $news->update([
            "image" => $path ? basename($path) : $news->image,
            "title" => $request->title,
            "description" => $request->description,
         ]);  
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось изменить новость.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Новость изменена.",
         "result" => [
            "id" => $news->id,
            "path" => $path ? Storage::url($path) : Storage::url('news/' . $news->image),
            "image" => $path ? basename($path) : $news->image,
            "title" => $request->title,
            "description" => $request->description,
         ],
      ], 200);
   }

   /* Публикация новости */
   public function publishNewsOnce(Request $request) {
      $validator = Validator::make($request->all(), [
         'id' => [
            'required',
            Rule::exists('news', 'id'),
         ],
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'exists' => 'Такой новости не существует (:attribute).',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $news = News::find($request->id);
      $message = $news->hide ? 'Снято с публикации.' : 'Опубликовано.';

      try {
         $newsUpdate = $news->update([
            "hide" => !$news->hide,
         ]);  
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось опубликовать новость.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => $message,
         "result" => $news->hide,
      ], 200);
   }

   /* Изменение новости */
   public function saveNewsChangesAll(Request $request) {
      $validator = Validator::make($request->all(), [
         'news' => 'nullable|array',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'array' => 'Поле :attribute должно быть массивом.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      // Транзакция
      DB::beginTransaction();

      try {
         // Удаление
         foreach ($request->news as $key => $value) {
            $news = News::find($value['id']);

            if ($value["delete"] === true){
               $news->delete();
               continue;
            };         

            $news->update([
               "hide" => $value['hide'],
            ]);
         };

         $news = News::all();

         // Получение всех файлов
         $filesNews = Storage::files('public/news');
         if($filesNews) {
            foreach ($filesNews as $fileKey => $fileValue) {
               $useFile = false;
               
               /* Проверка на использование файла */
               foreach ($news as $key => $value) {
                  if ($value['image'] === basename($fileValue)) {
                     $useFile = true;
                     break;
                  };

                  if (News::where('title', 'like', basename($fileValue)) || News::where('description', 'like', basename($fileValue))) {
                     $useFile = true;
                     break;
                  }
               };
               
               /* Удаление файла, если не используется */
               if (!$useFile) {
                  Storage::delete($fileValue);
               };
            };
         };

         // Фиксация транзакции
         DB::commit();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Новости изменены.",
            "result" => null,
         ], 200);
      } catch (Throwable $e) {
         // Отмена транзакции
         DB::rollBack();         
         
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ВИДЕО                         |*/
   /* |___________________________________________________|*/
   /* Добавление */
   public function saveVideoChanges(Request $request) {
      $validator = Validator::make($request->all(), [
         'videos' => 'nullable|array',
      ], [
         'array' => 'Поле :attribute должно быть массивом.',
      ]);

      if ($validator->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validator->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ]);
      };

      $arrayID = [];

      foreach ($request->videos as $key => $value) {
         // Удаление
         if ($value["delete"] === true) {
            $video = Video::find($value["id"]);
            $video->delete();
            
            continue;
         };

         // Добавление
         if ($value["create"] === true){
            $videoCreate = Video::create([
               "order" => $value["order"],
               "video" => $value["video"],
               "description" => $value["description"],
            ]);

            /* Запись нового объекта в массив */
            $arrayID[] = (object) [
               // Прошлый id
               'old' => $value["id"], 
               // Новый id
               'new' => $videoCreate->id
            ];
            continue;
         };

         // Обновление
         $video = Video::find($value["id"]);
         $video->update([
            "order" => $value["order"],
            "video" => $value["video"],
            "description" => $value["description"],
         ]);   
      };

      // Сортировка слайдов по порядку от наименьшего до наибольшого
      $videosAll = Video::all()->SortBy('order');

      // Обновление порядковых номеров
      $count = 0;
      foreach ($videosAll as $videoKey => $videoValue) {
         $count++;
         $videoValue["order"] = $count;
         $videoValue->save();
      };

      // Получение всех файлов
      $filesVideos = Storage::files('public/video');
      if($filesVideos) {
         foreach ($filesVideos as $fileKey => $fileValue) {
            $useFile = false;
            /* Проверка на использование файла */
            foreach ($videosAll as $aboutKey => $videoValue) {
               /* Проверка значения названия файла на совпадение */
               if ($videoValue->video == basename($fileValue)) {
                  $useFile = true;
               };
            };
   
            /* Удаление файла, если не используется */
            if (!$useFile) {
               Storage::delete($fileValue);
            };
         };
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные обновлены.",
         "result" => $arrayID,
      ]);
   }
}
