<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

use App\Models\Slide;
use App\Models\Footer;

use App\Models\Specialist;
use App\Models\Specialization;
use App\Models\SpecialistSpecialization;
use App\Models\Clinic;
use App\Models\SpecialistClinic;
use App\Models\Education;
use App\Models\SpecialistEducation;
use App\Models\Work;
use App\Models\SpecialistWork;
use App\Models\Certificate;
use App\Models\SpecialistCertificate;

use App\Models\About;

use App\Models\Contact;
use App\Models\Phone;
use App\Models\ContactPhone;
use App\Models\Mail;
use App\Models\ContactMail;

use App\Models\Shedule;
use App\Models\ShedulesDay;
use App\Models\ShedulesDaysTime;
use App\Models\ShedulesClinic;
use App\Models\ShedulesCurrentDay;

use App\Models\PriceAddress;
use App\Models\PriceCategory;
use App\Models\PriceValue;

use App\Models\News;
use App\Models\Video;

use App\Models\Tracking;

class HomeController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 БОТ ТЕЛЕГРАММ                     |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Отправка данных                                   */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function requestTelegramBot(Request $request) {
      // Получение данных
      $formData = $request->formData;
      $json = json_decode($formData, true);

      try {
         // Добавление заголовков
         $request->merge([
            'type' => 'form_request_telegram_bot',
            'meta' => 'Заявка - ' . $json['title'],
         ]);
   
         $this->createTrack($request);
   
         // Отправка сообщения
         $str =
            "Заявка: ". $json['title'] . "\n" . 
            "ФИО : " . $json['name'] . "\n" . 
            "Телефон : " . $json['phone'] . "\n" . 
            "Дата рождения : " . $json['date'] . "\n" . 
            "Специализация : " . $json['specialization'];
      
         $ch = curl_init("https://api.telegram.org/bot" . "6465405714:AAHJTFfNkmKgSwtOgQHV1HxAZovcalaAbNU" . "/sendMessage?" . http_build_query(   
            [
               "chat_id" => '6348437826',
               "text" => $str,
            ]
         ));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_HEADER, false);
         if (curl_exec($ch)) {
            curl_close($ch);   
   
            return response()->json([
               "status" => true,
               "message" => "Заявка отправлена.",
               "data" => null,
            ]);
         } else {
            curl_close($ch);   
            
            return response()->json([
               "status" => false,
               "message" => "Сервис отправки не работает.",
               "data" => null,
            ]);
         };
      } catch (Throwable $e) {
         return response()->json([
            "status" => false,
            "message" => "При отправке что-то пошло не так.",
            "data" => null,
         ]);
      }
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    СЛАЙДЕР                        |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Получение данных                                  */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Получение данных о всех слайдах */
   public function getSlidesAll(Request $request) {
      $slides = Slide::all();

      foreach ($slides as $key => $value) {
         // Добавление нового поля path, в котором хранится путь к изображению
         $slides[$key]->path = Storage::url('slides/' . $value->filename);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Слайды получены.",
         "result" => $slides,
      ], 200);
   } 

   /* Получение данных о всех слайдах, которые не скрыты */
   public function getSlidesNotHide(Request $request) {
      $slides = Slide::where('hide', false)->get();
      foreach ($slides as $key => $value) {
         $slides[$key]->path = Storage::url('slides/' . $value->filename);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Слайды получены.",
         "result" => $slides,
      ], 200);
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ФУТЕР                         |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Получение данных                                  */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Получение данных о футере */
   public function getFooter(Request $request) {
      $footer = Footer::find(1);

      if (!$footer) {
         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Футер не найден.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Футер найден.",
         "result" => $footer->description,
      ], 200);
   } 
   
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                  СПЕЦИАЛИСТЫ                      |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Врачи                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод всех докторов */ 
   public function getSpecialists(Request $request) {
      $specialists = Specialist::where('hide', false)->get();
      if($specialists) {
         foreach ($specialists as $key => $value) {
            $specialistsSpecializations = SpecialistSpecialization::where('id_specialist', $specialists[$key]->id)->get();
            if($specialistsSpecializations) {
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

            $specialistsClinics = SpecialistClinic::where('id_specialist', $specialists[$key]->id)->get();
            if($specialistsClinics) {
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

            $specialists[$key]->url = $this->makeUrl($specialists[$key]->family . " " . $specialists[$key]->name . " " . $specialists[$key]->surname);
            $specialists[$key]->path = Storage::url('specialists/' . $value->filename);      
            $specialists[$key]->specialization = $specializations;      
            $specialists[$key]->clinics = $clinics;      

            $specialists[$key]->name = $specialists[$key]->family . " " . $specialists[$key]->name . " " . $specialists[$key]->surname;
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
   public function getSpecialistProfile(Request $request) {
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
         $stringTransliterate = $this->makeUrl($specialists[$key]->family . " " . $specialists[$key]->name . " " . $specialists[$key]->surname);

         if ($request->url == $stringTransliterate) {
            if ($specialists[$key]->hide == true) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Специалист не найден.",
                  "result" => null,
               ], 500);
            };

            $specialists[$key]->path= Storage::url('specialists/' . $specialists[$key]->filename);

            $specialistsSpecializations = SpecialistSpecialization::where('id_specialist', $specialists[$key]->id)->get();
            if($specialistsSpecializations) {
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

            $specialistsEducations = SpecialistEducation::where('id_specialist', $specialists[$key]->id)->get();
            if($specialistsEducations) {
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

            $specialistsCertificates = SpecialistCertificate::where('id_specialist', $specialists[$key]->id)->get();
            if($specialistsCertificates) {
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
                  "profile" => $specialists[$key],
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
   public function getSpecialistsShort(Request $request) {
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
   public function getSpecialistAll(Request $request) {
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
      if($specialist) {
         $specialist->path = Storage::url('specialists/' . $specialist->filename);
   
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
   public function getSpecialistSections(Request $request) {
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
   public function getSpecializationsAll(Request $request) {
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
   public function getClinicsAll(Request $request) {
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
   /* |                      ЦЕНЫ                         |*/
   /* |___________________________________________________|*/
   /* Вывод адресов с ценами */
   public function getPricesChoice(Request $request) {
      $priceAddresses = PriceAddress::all();

      $cityFlags = array("г. ", "город", "р.п.", "р. п.", "п.", "п. ");
      $streetFlags = array("ул. ", "улица ");
      $houseFlags = array("д.", "д. ", "дом", "дом ",);

      $formatPriceAddresses = [];
      foreach($priceAddresses as $key => $value) {
         $splitAddress = explode(', ', $value->name);

         trim($splitAddress[0]);

         $city = trim(str_ireplace($cityFlags, "", $splitAddress[0]), " ");
         $street = trim(str_ireplace($streetFlags, "", $splitAddress[1]), " ");
         $house = trim(str_ireplace($houseFlags, "", $splitAddress[2]), " ");

         $formatPriceAddresses[] = (object) [
            "city" => $city,
            "cityUrl" => $this->makeUrl($city),
            "street" => $street,
            "streetUrl" => $this->makeUrl($street),
            "house" => $house,
            "houseUrl" => $this->makeUrl($house),
         ];
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Адреса успешно получены.",
         "result" => $formatPriceAddresses,
      ], 200);
   }   

   /* Вывод шаблона цен */
   public function getPricesTemplate(Request $request) {
      // Валидация
      $validated = Validator::make($request->all(), [
         'city' => 'required',
         'street' => 'required',
         'house' => 'required',
      ], [
         'city.required' => 'Отсутствует город.',
         'street.required' => 'Отсутствует улица.',
         'house.required' => 'Отсутствует номер дома.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $priceAddresses = PriceAddress::all();

      // Флаги для поиска
      $cityFlags = array("г. ", "город", "р.п.", "р. п.", "п.", "п. ");
      $streetFlags = array("ул. ", "улица ");
      $houseFlags = array("д.", "д. ", "дом", "дом ",);

      // Текущий адрес
      $currentAddress = null;
      
      // Все адреса
      $addresses = [];
      
      try {
         // Формирование ссылок на клиники в заголовке
         foreach($priceAddresses as $key => $value) {
            $splitAddress = explode(', ', $value->name);
   
            $city = trim(str_ireplace($cityFlags, "", $splitAddress[0]), " ");
            $street = trim(str_ireplace($streetFlags, "", $splitAddress[1]), " ");
            $house = trim(str_ireplace($houseFlags, "", $splitAddress[2]), " ");
   
            if ($this->makeUrl($city) != $request->city) {
               continue;
            };
   
            if ($this->makeUrl($street) != $request->street) {
               continue;
            };
   
            if ($this->makeUrl($house) != $request->house) {
               continue;
            };
   
            $currentAddress = $value;
            break;
         };
   
         $categories = PriceCategory::where('addressId', $currentAddress->id)->get();
         if (!$categories) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Категории не найдены.",
               "result" => null,
            ], 500);
         };
   
         // Форматируем категории
         $categoriesFormat = [];
         foreach($categories as $categoriesKey => $categoriesValue) {
            if ($categoriesValue->categoryId != null) continue;
            
            $categoriesFormat[] = $this->getCategoryArray($categoriesValue);
         }
   
         // Получаем ID категорий
         $categoryIds = $categories->pluck('id');
   
         // Получаем цены, привязанные к этим категориям
         $prices = PriceValue::whereIn('categoryId', $categoryIds)->get();
         
         if (!$currentAddress) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Адрес не найден.",
               "result" => null,
            ], 500);
         };
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не получилось получить данные.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Адрес успешно получен.",
         "result" => (object) [
            "address" => $currentAddress,
            "categories" => $categoriesFormat,
            "categoriesList" => $categories,
            "prices" => $prices
         ],
      ], 200);
   }

   /* Вывод всех данных из таблиц с ценами */
   public function getPricesAll(Request $request) {
      $priceAddresses = PriceAddress::all();
      
      if (!$priceAddresses) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить адреса.",
            "result" => null,
         ], 500);
      };
      
      $priceCategories = PriceCategory::all();
      
      if (!$priceCategories) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить категории.",
            "result" => null,
         ], 500);
      }

      // Форматируем категории
      $priceCategoriesFormat = [];
      foreach($priceCategories as $priceCategoriesKey => $priceCategoriesValue) {
         if ($priceCategoriesValue->categoryId != null) continue;
         
         $priceCategoriesFormat[] = $this->getCategoryArray($priceCategoriesValue);
      }

      $priceValues = PriceValue::all();
      if (!$priceValues) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить услуги и цены.",
            "result" => null,
         ], 500);
      }

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Данные успешно получены.",
         "result" => [
            "adresses" => $priceAddresses,
            "categories" => $priceCategoriesFormat,
            "categoriesList" => $priceCategories,
            "prices" => $priceValues,
         ],
      ], 200);
   }

   /* Форматирование категорий */
   public function getCategoryArray($category) {
      $categoryFormat = (object) [
         "id" => $category->id,
         "name" => $category->name,
         "children" => [],
      ];

      // Проверяем наличие подкатегорий
      $innerCategories = PriceCategory::where('categoryId', $category->id)->get();
      
      if (count($innerCategories) > 0) {
         foreach ($innerCategories as $innerCategoriesKey => $innerCategoriesValue) {
            $innerCategories[$innerCategoriesKey] = $this->getCategoryArray($innerCategoriesValue);
         }

         $categoryFormat->children = $innerCategories;
      }
      
      return $categoryFormat;
   }

   /* Получение групп цен */
   public function getPricesGruop(Request $request) {
      // Валидация
      $validated = Validator::make($request->all(), [
         'group' => 'required',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $group = $request->group;
      $title = "";
      $array = [];

      switch ($group) {
         case 'travels':
            $title = "Путевки";

            // Получение всех адресов
            $addresses = PriceAddress::all();

            // Перебор адресов
            foreach ($addresses as $addressesKey => $addressesValue) {
               $categories = [];
                  
               // Поиск нужных категорий
               $categories = PriceCategory::where('addressId', '=', $addressesValue->id)
                  ->where(function($query) {
                  $query->where('name', 'like', '%Путевки%')
                     ->orWhere('name', 'like', '%путевки%')
                     ->orWhere('name', 'like', '%Путёвки%')
                     ->orWhere('name', 'like', '%путёвки%')
                     ->orWhere('name', 'like', '%Комплексные программы%');
               })->get();   

               // Проверка на наличие категорий
               if (count($categories) > 0) {
                  $categoriesIds = $categories->pluck('id')->toArray();

                  $prices = PriceValue::whereIn('categoryId', $categoriesIds)->get()->groupBy('categoryId');

                  // Проверяем наличие цен
                  foreach ($categories as $categoryKey => $categoryValue) {
                     $categoryValue->prices = $prices[$categoryValue->id];
                  }
      
                  $array[] = [
                     "id" => $addressesValue->id,
                     "name" => $addressesValue->name,
                     "categories" => $categories,
                  ];
               }
            }            
            break;
         case 'plastic':
            $title = "Пластика";

            // Получение всех цен, которые имеют вхождения по названию
            $prices = PriceValue::where(function($query) {
               $query->where('name', 'like', '%Блефаропластика%')
                  ->orWhere('name', 'like', '%блефаропластика%')
                  ->orWhere('name', 'like', '%Булхорн%')
                  ->orWhere('name', 'like', '%булхорн%');
            })->get()->groupBy('categoryId');   

            // Получение id всех категорий, где есть такие цены
            $categoryIds = $prices->keys()->toArray();

            // Получение всех категорий
            $categories = PriceCategory::whereIn('id', $categoryIds)->get()->groupBy('addressId');
            
            // Получение id всех категорий, где есть такие цены
            $addressesIds = $categories->keys()->toArray();
            $addresses = PriceAddress::whereIn('id', $addressesIds)->get();

            foreach ($addresses as $addressesKey => $addressesValue) {
               $categories = $categories[$addressesValue->id];

               foreach($categories as $categoryKey => $categoryValue) {
                  $categoryValue->prices = $prices[$categoryValue->id];
               };

               $array[] = [
                  "id" => $addressesValue->id,
                  "name" => $addressesValue->name,
                  "categories" => $categories,
               ];
            };
            break;            
         default:
            $title = "Не найдено";

            $categories = [];
            break;
      }   

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => [
            "array" => $array ?? [],
            "title" => $title
         ],
      ], 200);
   }
   
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                   РАСПИСАНИЕ                      |*/
   /* |___________________________________________________|*/
   /* Получение расписания */
   public function getShedulesAll(Request $request) {
      try {

         // Загрузка ВСЕХ основных данных сразу 
         $schedules = Shedule::all(); 
         $scheduleClinics = ShedulesClinic::all();
         $currentDays = ShedulesCurrentDay::all();
   
         // Подготовка ID для массовой загрузки связанных данных
         $scheduleIds = $schedules->pluck('id');
         $clinicIds = $scheduleClinics->pluck('id'); 
   
         // Загрузка ВСЕХ дней расписаний за ОДИН запрос
         $scheduleDays = ShedulesDay::whereIn('sheduleId', $scheduleIds)
            ->whereIn('clinicId', $clinicIds)
            ->get()
            ->groupBy(['sheduleId', 'clinicId']);
   
   
         // Загрузка ВСЕХ времен для дней за ОДИН запрос
         $dayIds = $scheduleDays->flatten()->pluck('id'); // Получаем все ID дней
         $scheduleTimes = ShedulesDaysTime::whereIn('dayId', $dayIds)
            ->get()
            ->groupBy('dayId');
   
         $specialists = Specialist::all()->groupBy(function ($specialist) {
            return $specialist->family . ' ' . $specialist->name . ' ' . $specialist->surname;
         });
   
         // Создаем "карту" текущих дней для мгновенного поиска по дате
         $currentDaysMap = $currentDays->keyBy('date');
   
         // Основной цикл по расписаниям
         foreach ($schedules as $schedule) {
            $weeks = []; // Здесь будем хранить данные для каждой клиники
   
            /* Получение специалистов */
            $sheduleSpecialits = $specialists[$schedule->name] ?? null;
            if($sheduleSpecialits) {
               $schedule->image = Storage::url('specialists/' . $sheduleSpecialits[0]->filename);
               $schedule->link = $this->makeUrl($sheduleSpecialits[0]->family . ' ' . $sheduleSpecialits[0]->name . ' ' . $sheduleSpecialits[0]->surname);
            } else { 
               $schedule->image = null;
            }
            
            // Цикл по клиникам для текущего расписания
            foreach ($scheduleClinics as $clinic) {
               // Получаем дни для данной пары (расписание + клиника)
               // Используем группировку, чтобы избежать дополнительных запросов
               $daysForClinic = $scheduleDays
                     ->get($schedule->id, collect()) // Ищем по ID расписания
                     ->get($clinic->id, collect());  // Затем по ID клиники
   
               // Если есть дни для этой клиники
               if ($daysForClinic->isNotEmpty()) {
                     // Формируем контент для всех текущих дней
                     $content = $currentDays->map(function ($day) use ($daysForClinic, $scheduleTimes) {
                        // Ищем день с такой же датой
                        $matchingDay = $daysForClinic->firstWhere('date', $day->date);
                        
                        // Формируем объект дня
                        return [
                           "id" => $day->id,
                           "date" => $day->date,
                           "time" => $matchingDay 
                                 ? $scheduleTimes->get($matchingDay->id, collect())->pluck('name')->all()
                                 : [], // Если нет совпадения - пустой массив
                        ];
                     })->all();
   
                     $weeks[] = (object) [
                        "clinicId" => $clinic->id,
                        "status" => true,
                        "content" => $content,
                     ];
               } else {
                     // Если нет дней для этой клиники
                     $weeks[] = (object) [
                        "clinicId" => $clinic->id,
                        "status" => false,
                        "content" => $currentDays->map(function ($day) {
                           return [
                                 "id" => $day->id,
                                 "date" => $day->date,
                                 "time" => [],
                           ];
                        })->all(),
                     ];
               }
            }
   
            // Сохраняем сформированные данные в объект расписания
            $schedule->weeks = $weeks;
         }
   
         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "currentDays" => $currentDays,
               "shedules" => $schedules,
               "sheduleClinics" => $scheduleClinics,
               "specialists" => $specialists,
            ],
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => false,
            "message" => "Произошла ошибка.",
            "result" => null,
         ], 500);
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     О НАС                         |*/
   /* |___________________________________________________|*/
   public function getAboutsAll(Request $request) {
      try {         
         $about = About::all();
   
         foreach ($about as $key => $value) {
            $value->pathOne = Storage::url('abouts/' . $value->imageOne);
            $value->pathTwo = Storage::url('abouts/' . $value->imageTwo);
            $value->pathThree = Storage::url('abouts/' . $value->imageThree);
         };
   
         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $about,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);         
      };
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    КОНТАКТЫ                       |*/
   /* |___________________________________________________|*/
   public function getContactsAll(Request $request) {
      try {
         $clinics = Clinic::all();
         $contacts = Contact::all();
      
         foreach ($contacts as $contactKey => $contactValue) {
            $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
            $phones = [];

            foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
               $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
            };
            $contactValue->phones = $phones; 
   
            $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
            $mails = [];

            foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
               $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
            };
            $contactValue->mails = $mails; 
         };
   
         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "contacts" => $contacts,
               "clinics" => $clinics,
            ],
         ], 200);   
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      }
   }

   /* Получение контактов и клиник */
   public function getContactsClinicsAll(Request $request) {
      try {
         $contacts = Contact::all();
         
         foreach ($contacts as $contactKey => $contactValue) { 
            $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
            $phones = [];

            foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
               $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
            };
            $contactValue->phones = $phones; 
   
            $contactMails = ContactMail::where('contactId', $contactValue->id)->get();   
            $mails = [];

            foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
               $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
            };
            $contactValue->mails = $mails;         
            $contactValue->clinic = Clinic::where('id', $contactValue->clinicId)->first();
         }
   
         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $contacts,
         ], 200);   
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      }

   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     НОВОСТИ                       |*/
   /* |___________________________________________________|*/
   /* Получение всех новостей */
   public function getNewsAll(Request $request) {
      try {
         $news = News::all()->sortByDesc('created_at')->values()->all();

         foreach ($news as $key => $value) {
            $value->date = Carbon::parse($value->created_at)->format('d.m.Y H:i:s');
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
         };
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);            
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => $news,
      ], 200);
   }
   
   /* Получение всех новостей */
   public function getNewsShort(Request $request) {
      $validator = Validator::make($request->all(), [
         'limit' => 'required|integer',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'integer' => 'Поле :attribute должно быть числом.',
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
         $news = News::all()->where('hide', 1)->sortByDesc('created_at')->take($request->limit)->values()->all();

         foreach ($news as $key => $value) {
            $value->date = Carbon::parse($value->created_at)->format('d.m.Y H:i:s');
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
         };
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);            
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => [
            "news" => $news,
            "count" => News::all()->where('hide', 1)->count(),
         ],
      ], 200);
   }

   /* Получение одной новости */
   public function getNewsOnce(Request $request) {
      $validator = Validator::make($request->all(), [
         'date' => 'required|date_format:d.m.Y',
         'time' => 'required|date_format:H:i:s',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'date_format' => 'Некорректный формат поля :attribute.',
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
         $news = News::whereDate('created_at', Carbon::parse($request->date))->whereTime('created_at', '=', $request->time)->first();         
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };

      if (!$news) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Такой новости нет.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => [
            "id" => $news->id,
            "path" => Storage::url('news/' . $news->image),
            "image" => $news->image,
            "title" => $news->title,
            "description" => $news->description,
            "hide" => $news->hide,
         ],
      ], 200);
   }

   /* Получение одной новости */
   public function getNewsOnceWithout(Request $request) {
      $validator = Validator::make($request->all(), [
         'date' => 'required|date_format:d.m.Y',
         'time' => 'required|date_format:H:i:s',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'date_format' => 'Некорректный формат поля :attribute.',
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
         $news = News::whereDate('created_at', Carbon::parse($request->date))->whereTime('created_at', '=', $request->time)->first();
         
         if (!$news->hide) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Такой новости нет.",
               "result" => null,
            ], 500);
         }
      
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);
      };
      
      if (!$news) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Такой новости нет.",
            "result" => null,
         ], 500);
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Новости получены.",
         "result" => [
            "id" => $news->id,
            "path" => Storage::url('news/' . $news->image),
            "image" => $news->image,
            "title" => $news->title,
            "description" => $news->description,
            "hide" => $news->hide,
         ],
      ], 200);
   }

   /* Получение одной новости */
   public function getNewsMore(Request $request) {
      $validator = Validator::make($request->all(), [
         'date' => 'required|date_format:d.m.Y',
         'time' => 'required|date_format:H:i:s',
         'limit' => 'required|integer',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
         'date_format' => 'Некорректный формат поля :attribute.',
         'integer' => 'Поле :attribute должно быть числом.',
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

      $date = Carbon::parse($request->date . ' ' . $request->time);

      try {
         $news = News::where('created_at', '<', $date)->where('hide', 1)->get();
         
         $newsSorted = $news->sortByDesc('created_at')->take($request->limit)->values()->all();

         foreach ($newsSorted as $key => $value) {
            $value->date = Carbon::parse($value->created_at)->format('d.m.Y H:i:s');
            $value->url_date = Carbon::parse($value->created_at)->format('d.m.Y');
            $value->url_time = Carbon::parse($value->created_at)->format('H:i:s');
            $value->path = Storage::url('news/' . $value->image);
         };
      } catch (Throwable $th) {
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
         "message" => "Новости получены.",
         "result" => [
            "news" => $newsSorted,
            "count" => News::all()->where('hide', 1)->count(),
         ],
      ]);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      ВИДЕО                        |*/
   /* |___________________________________________________|*/
   /* Получение всех видео */
   public function getVideosAll(Request $request) {
      try {
         $videos = Video::all()->sortBy('order')->values();

         foreach ($videos as $key => $value) {
            $value->path = Storage::url('video/' . $value->video);
         };
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось получить данные.",
            "result" => null,
         ], 500);            
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Не удалось получить данные.",
         "result" => $videos,
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    СТАТИСТИКА                     |*/
   /* |___________________________________________________|*/
   public function createTrack(Request $request) {
      try {
         // Поиск браузера
         $browser = match (true) {
            str_contains($request->userAgent(), 'Edge') => 'Edge',
            str_contains($request->userAgent(), 'Opera') => 'Opera',
            str_contains($request->userAgent(), 'Firefox') => 'Firefox',
            str_contains($request->userAgent(), 'Chrome') => 'Chrome',
            str_contains($request->userAgent(), 'Safari') => 'Safari',
            default => 'Unknown',
         };

         $track = Tracking::create([
            "type" => $request->type ?? "not defined",
            "ip" => $request->ip() ?? "not defined",
            "user_agent" => $browser ?? "not defined",
            "meta" => $request->meta ?? "not defined",
         ]);
      } catch (Throwable $e) {
         return response()->json([
            "status" => false,
            "message" => "Произошла ошибка при загрузке.",
            "data" => null,
         ]);         
      };

      return response()->json([
         "status" => true,
         "message" => "Данные успешно получены.",
         "data" => false,
      ]);
   }
};

