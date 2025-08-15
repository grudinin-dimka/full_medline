<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

/* Ошибки */
use Throwable;

/* Помощники */
use Carbon\Carbon;
use XMLWriter;
use Transliterator;

/* Модели */
use App\Models\Specialist\Specialist;
use App\Models\Shared\Work;
use App\Models\Specialist\SpecialistWork;
use App\Models\Shared\Certificate;
use App\Models\Specialist\SpecialistCertificate;
use App\Models\Shared\Clinic;
use App\Models\Specialist\SpecialistClinic;
use App\Models\Shared\Education;
use App\Models\Specialist\SpecialistEducation;
use App\Models\Shared\Specialization;
use App\Models\Specialist\SpecialistSpecialization;

class SpecialistController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Вывод всех докторов */
   public function getSpecialists(Request $request)
   {
      $specialists = Specialist::where('hide', false)->get();
      if ($specialists) {
         foreach ($specialists as $key => $value) {
            $specialistsSpecializations = SpecialistSpecialization::where('id_specialist', $value->id)->get();
            if ($specialistsSpecializations) {
               $specializations = [];
               foreach ($specialistsSpecializations as $specialistsSpecializationsKey => $specialistsSpecializationsValue) {
                  $specializations[] = Specialization::find($specialistsSpecializationsValue->id_specialization);
               };
            } else {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось получить специализации.",
                  "result" => null,
               ], 500);
            };

            $specialistsClinics = SpecialistClinic::where('id_specialist', $value->id)->get();
            if ($specialistsClinics) {
               $clinics = [];
               foreach ($specialistsClinics as $specialistsClinicsKey => $specialistsClinicsValue) {
                  $clinics[] = Clinic::find($specialistsClinicsValue->id_clinic);
               };
            } else {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось получить клиники.",
                  "result" => null,
               ], 500);
            };

            $value->url = $this->makeUrl($specialists[$key]->family . " " . $specialists[$key]->name . " " . $specialists[$key]->surname);
            $value->path = $value->path();
            $value->specialization = $specializations;
            $value->clinics = $clinics;

            $value->name = $value->family . " " . $value->name . " " . $value->surname;
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Специалисты найдены.",
            "result" => $specialists,
         ], 200);
      } else {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Специалисты не найдены.",
            "result" => null,
         ], 500);
      };
   }

   /* Вывод конкретного доктора */
   public function getSpecialistProfile(Request $request)
   {
      // Валидация
      $validated = Validator::make($request->all(), [
         'url' => 'required',
      ], [
         'url.required' => 'Поле url обязательно для заполнения.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Ошибка валидации входных данных.",
            "result" => null,
         ], 422);
      };

      $specialists = Specialist::all();

      foreach ($specialists as $key => $value) {
         $stringTransliterate = $this->makeUrl($value->family . " " . $value->name . " " . $value->surname);

         if ($request->url == $stringTransliterate) {
            if ($value->hide) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Специалист не найден.",
                  "result" => null,
               ], 500);
            };

            $value->path = $value->path();

            $specialistsSpecializations = SpecialistSpecialization::where('id_specialist', $value->id)->get();
            if ($specialistsSpecializations) {
               $specializations = [];
               foreach ($specialistsSpecializations as $specialistsSpecializationsKey => $specialistsSpecializationsValue) {
                  $specializations[] = Specialization::find($specialistsSpecializationsValue->id_specialization);
               };
            } else {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось получить специализации.",
                  "result" => [
                     "profile" => null,
                     "specializations" => null,
                     "educations" => null,
                  ],
               ], 500);
            };

            $specialistsEducations = SpecialistEducation::where('id_specialist', $value->id)->get();
            if ($specialistsEducations) {
               $educations = [];
               foreach ($specialistsEducations as $specialistsEducationsKey => $specialistsEducationsValue) {
                  $educations[] = Education::find($specialistsEducationsValue->id_education);
               };
            } else {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось получить образования.",
                  "result" => [
                     "profile" => null,
                     "specializations" => null,
                     "educations" => null,
                  ],
               ], 500);
            }

            $specialistsCertificates = SpecialistCertificate::where('id_specialist', $value->id)->get();
            if ($specialistsCertificates) {
               $certificates = [];
               foreach ($specialistsCertificates as $specialistsCertificatesKey => $specialistsCertificatesValue) {
                  $certificates[] = Certificate::find($specialistsCertificatesValue->id_certificate);
               };
            } else {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось получить сертификаты.",
                  "result" => [
                     "profile" => null,
                     "specializations" => null,
                     "educations" => null,
                  ],
               ], 500);
            }

            return response()->json([
               "success" => true,
               "debug" => false,
               "message" => "Данные получены.",
               "result" => [
                  "profile" => $value,
                  "specializations" => $specializations,
                  "educations" => $educations,
                  "certificates" => $certificates,
               ],
            ], 200);
         };
      };

      return response()->json([
         "success" => false,
         "debug" => true,
         "message" => "Данного специалиста не существует.",
         "result" => null,
      ], 500);
   }

   /* Вывод сокращенной информации о врачах */
   public function getSpecialistsShort(Request $request)
   {
      $specialistsShort = Specialist::select('id', 'family', 'name', 'surname', 'hide')->get();

      foreach ($specialistsShort as $key => $value) {
         $specializations = SpecialistSpecialization::where('id_specialist', $value->id)->get();

         $specializationsNames = "";
         // Перебор специализаций врача
         foreach ($specializations as $key => $valueSpecialization) {
            // Получение названия специализации
            $specializationsNamesOnce = Specialization::where('id', $valueSpecialization->id_specialization)->get();

            $specializationsNames .= $specializationsNamesOnce[0]->name . ", ";
         };
         $specializationsNames = substr($specializationsNames, 0, -2);

         $value->specializations = $specializationsNames;
         $value->name = $value->family . " " . $value->name . " " . $value->surname;;
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => $specialistsShort,
      ], 200);
   }

   /* Вывод полной информации о враче */
   public function getSpecialistAll(Request $request)
   {
      // Валидация
      $validated = Validator::make($request->all(), [
         'id' => 'required',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Ошибка валидации входных данных.",
            "result" => null,
         ], 422);
      };

      $specialist = Specialist::find($request->id);
      if ($specialist) {
         $specialist->path = $specialist->path();

         // Получение данных о специализациях
         $specialistCertificates = SpecialistCertificate::where('id_specialist', $request->id)->get();
         $certificates = Certificate::all();
         $includeSpecialistCertificates = [];
         foreach ($specialistCertificates as $key => $valueSpecialistCertificates) {
            foreach ($certificates as $key => $valueCertificate) {
               if ($valueSpecialistCertificates->id_certificate == $valueCertificate->id) {
                  $includeSpecialistCertificates[] = $valueCertificate;
               }
            }
         };

         // Получение данных о обучениях
         $specialistEducations = SpecialistEducation::where('id_specialist', $request->id)->get();
         $educations = Education::all();
         $includeSpecialistEducations = [];
         foreach ($specialistEducations as $key => $valueSpecialistEducations) {
            foreach ($educations as $key => $valueEducations) {
               if ($valueSpecialistEducations->id_education == $valueEducations->id) {
                  $includeSpecialistEducations[] = $valueEducations;
               }
            }
         };

         // Получение данных о прошлых работах
         $specialistWorks = SpecialistWork::where('id_specialist', $request->id)->get();
         $works = Work::all();
         $includeSpecialistWorks = [];
         foreach ($specialistWorks as $key => $valueSpecialistWorks) {
            foreach ($works as $key => $valueWorks) {
               if ($valueSpecialistWorks->id_work == $valueWorks->id) {
                  $includeSpecialistWorks[] = $valueWorks;
               }
            }
         }

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Специалист найден.",
            "result" => [
               "specialist" => [
                  "profile" => $specialist,
                  "connections" => [
                     "specializations" => SpecialistSpecialization::where('id_specialist', $request->id)->get(),
                     "clinics" => SpecialistClinic::where('id_specialist', $request->id)->get(),
                     "certificates" => $includeSpecialistCertificates,
                     "educations" => $includeSpecialistEducations,
                     "works" => $includeSpecialistWorks,
                  ],
               ],
               "sections" => [
                  "specializations" => Specialization::all(),
                  "clinics" => Clinic::all(),
               ],
            ],
         ], 200);
      } else {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Специалист не найден.",
            "result" => null,
         ], 500);
      };
   }

   /* Вывод полной информации о враче */
   public function getSpecialistSections(Request $request)
   {
      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => [
            "specializations" => Specialization::all(),
            "certificates" => Certificate::all(),
            "clinics" => Clinic::all(),
            "educations" => Education::all(),
            "works" => Work::all(),
         ],
      ], 200);
   }

   /* _____________________________________________________*/
   /* 2. Специализации                                     */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "специализации" */
   public function getSpecializationsAll(Request $request)
   {
      $specializations = Specialization::all();

      if (!$specializations) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => $specializations,
      ], 200);
   }

   /* _____________________________________________________*/
   /* 3. Клиники                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "клиники" */
   public function getClinicsAll(Request $request)
   {
      $clinics = Clinic::all();

      if (!$clinics) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => $clinics,
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Модульное сохранение */
   public function saveSpecialistModular(Request $request)
   {
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
   public function saveSpecialistProfile(Request $request)
   {
      $profile = json_decode($request->profile);

      $path = null;
      if ($request->hasFile('image')) {
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
   public function saveSpecialistSpecializations(Request $request)
   {
      $specializations = json_decode($request->specializations);
      $id = json_decode($request->id);

      $specialist = Specialist::find($id);
      if ($specialist) {
         // Поиск или добавление 
         foreach ($specializations as $key => $value) {
            SpecialistSpecialization::firstOrCreate([
               'id_specialist' => $value->id_specialist,
               'id_specialization' => $value->id_specialization,
            ]);
         };

         // Поиск в массиве
         $specialistSpecializations = SpecialistSpecialization::where('id_specialist', $id)->get();

         if ($specialistSpecializations) {
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
   public function saveSpecialistClinics(Request $request)
   {
      $clinics = json_decode($request->clinics);
      $id = json_decode($request->id);

      $specialist = Specialist::find($id);

      if ($specialist) {
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
   public function saveSpecialistCertificates(Request $request)
   {
      $certificates = json_decode($request->certificates);
      $id = json_decode($request->id);

      $specialist = Specialist::find($id);
      if ($specialist) {
         $arrayID = [];
         foreach ($certificates as $key => $value) {
            // Удаление
            if ($value->delete === true) {
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

               if ($certificateCreate) {
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

            if (!$certificateUpdate) {
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
   public function saveSpecialistEducations(Request $request)
   {
      $educations = json_decode($request->educations);
      $id = json_decode($request->id);

      $specialist = Specialist::find($id);
      if ($specialist) {
         $arrayID = [];

         foreach ($educations as $key => $value) {
            // Удаление
            if ($value->delete === true) {
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

               if ($educationCreate) {
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

            if (!$educationUpdate) {
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
   public function saveSpecialistWorks(Request $request)
   {
      $works = json_decode($request->works);
      $id = json_decode($request->id);

      $specialist = Specialist::find($id);
      if ($specialist) {
         $arrayID = [];

         foreach ($works as $key => $value) {
            // Удаление
            if ($value->delete === true) {
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

               if ($workCreate) {
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

            if (!$workUpdate) {
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
   public function saveSpecialistsChanges(Request $request)
   {
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

      if ($filesSpecialists) {
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
   public function addSpecialist(Request $request)
   {
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
   public function saveSpecializationsChanges(Request $request)
   {
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
         if ($value["delete"] === true) {
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
   public function saveClinicsChanges(Request $request)
   {
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
         if ($value["delete"] === true) {
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
   public function makeSpecialistsXML(Request $request)
   {
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
}
