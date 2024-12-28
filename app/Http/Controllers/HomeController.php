<?php

namespace App\Http\Controllers;

use Transliterator;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                 БОТ ТЕЛЕГРАММ                     |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Отправка данных                                   */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   public function requestTelegramBot(Request $request) {
      $formData = $request->formData;
      $json = json_decode($formData, true);
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
            "message" => "Заявку не удалость отправить.",
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
   // Получение данных о всех слайдах
   public function getSlidesAll(Request $request) {
      $slides = Slide::all();
      foreach ($slides as $key => $value) {
         // Добавление нового поля path, в котором хранится путь к изображению
         $slides[$key]->path = Storage::url('slides/' . $value->filename);
      };
      return $slides;
   } 
   // Получение данных о всех слайдах, которые не скрыты
   public function getSlidesNotHide(Request $request) {
      $slides = Slide::where('hide', false)->get();
      foreach ($slides as $key => $value) {
         $slides[$key]->path = Storage::url('slides/' . $value->filename);
      };
      return $slides;
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     ФУТЕР                         |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Получение данных                                  */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   // Получение данных о футере
   public function getFooter(Request $request) {
      $footer = Footer::find(1);

      $data = [
         "title" => $footer->title,        
         "titleDesc" => $footer->titleDesc,        
         "license" => $footer->license,        
         "licenseDesc" => $footer->licenseDesc,        
         "footer" => $footer->footer,        
      ];
         
      return $data;
   } 
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                  СПЕЦИАЛИСТЫ                      |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Врачи                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод всех докторов */ 
   public function getSpecialists(Request $request) {
      $specializations = Specialization::all();

      $specialists = Specialist::where('hide', false)->get();
      if($specialists) {
         foreach ($specialists as $key => $value) {
            $specialists[$key]->url = makeUrl($specialists[$key]->family . " " . $specialists[$key]->name . " " . $specialists[$key]->surname);
            $specialists[$key]->path = Storage::url('specialists/' . $value->filename);      
         };

         return response()->json([
            "status" => true,
            "message" => "Специалисты найдены.",
            "data" => $specialists,
         ]);
      } else {
         return response()->json([
            "status" => false,
            "message" => "Специалисты не найдены.",
            "data" => null,
         ]);
      };
   }
   /* Вывод конкретного доктора */
   public function getSpecialistProfile(Request $request) {
      $specialists = Specialist::all();

      foreach ($specialists as $key => $value) {
         $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($specialists[$key]->family . " " . $specialists[$key]->name . " " . $specialists[$key]->surname);
         $stringUnderCase = strtolower($stringTransliterate);
         $stringReplace = str_replace(" ", "-", $stringUnderCase);
         
         if ($request->url == $stringReplace) {
            if ($specialists[$key]->hide == true) {
               return response()->json([
                  "status" => false,
                  "message" => "Специалист не найден.",
                  "data" => null,
               ]);
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
                  "status" => false,
                  "message" => "Не удалось получить специализации.",
                  "data" => (object)[
                     "profile" => null,
                     "specializations" => null,
                     "educations" => null,
                  ],
               ]);    
            };

            $specialistsEducations = SpecialistEducation::where('id_specialist', $specialists[$key]->id)->get();
            if($specialistsEducations) {
               $educations = [];
               foreach ($specialistsEducations as $specialistsEducationsKey => $specialistsEducationsValue) {
                  $educations[] = Education::find($specialistsEducationsValue->id_education);
               };               
            } else {
               return response()->json([
                  "status" => false,
                  "message" => "Не удалось получить специализации.",
                  "data" => (object)[
                     "profile" => null,
                     "specializations" => null,
                     "educations" => null,
                  ],
               ]);    
            }

            return response()->json([
               "status" => true,
               "message" => "Данные получены.",
               "data" => (object)[
                  "profile" => $specialists[$key],
                  "specializations" => $specializations,
                  "educations" => $educations,
               ],
            ]);            
         };
      };

      return response()->json([
         "status" => false,
         "message" => "Данного специалиста не существует.",
         "data" => null,
      ]);            
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
      };

      // $SpecialistSpecializations = SpecialistSpecialization::where('id_specialist', 1)->get();
      return $specialistsShort;
   }
   /* Вывод полной информации о враче */
   public function getSpecialistAll(Request $request) {
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
            "status" => true,
            "message" => "Специалист найден.",
            "data" => (object) [               
               "specialist" => (object) [
                  "profile" => $specialist,
                  "connections" => (object) [
                     "specializations" => SpecialistSpecialization::where('id_specialist', $request->id)->get(),
                     "clinics" => SpecialistClinic::where('id_specialist', $request->id)->get(),
                     "certificates" => $includeSpecialistCertificates,
                     "educations" => $includeSpecialistEducations,
                     "works" => $includeSpecialistWorks,                  
                  ],
               ],
               "sections" => (object) [
                  "specializations" => Specialization::all(),
                  "clinics" => Clinic::all(),
               ],
            ],
         ]);
      } else {
         return response()->json([
            "status" => false,
            "message" => "Специалист не найден.",
            "data" => null,
         ]);

      };

   }  
   /* Вывод полной информации о враче */
   public function getSpecialistSections(Request $request) {
      return response()->json([
         "specializations" => Specialization::all(),
         "certificates" => Certificate::all(),
         "clinics" => Clinic::all(),
         "educations" => Education::all(),
         "works" => Work::all(),
      ]);
   }
   /* _____________________________________________________*/
   /* 2. Специализации                                     */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "специализации" */
   public function getSpecializationsAll(Request $request) {
      $specializations = Specialization::all();
      return $specializations;
   }
   /* _____________________________________________________*/
   /* 3. Клиники                                           */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "клиники" */
   public function getClinicsAll(Request $request) {
      $clinics = Clinic::all();
      return $clinics;
   }
   /* _____________________________________________________*/
   /* 4. Образования                                       */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "образование" */
   public function getEducationsAll(Request $request) {
      $educations = Education::all();

      return response()->json([
         "educations" => $educations,
      ]);
   }
   /* _____________________________________________________*/
   /* 5. Работы                                            */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "работы" */
   public function getWorksAll(Request $request) {
      $works = Work::all();
      return $works;
   }
   /* _____________________________________________________*/
   /* 6. Работы                                            */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Вывод данных из таблицы "работы" */
   public function getCertificatesAll(Request $request) {
      $certificate = Certificate::all();
      return $certificate;
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                     О НАС                         |*/
   /* |___________________________________________________|*/
   public function getAboutsAll(Request $request) {
      $about = About::all();
      if(!$about) {
         return response()->json([
            "status" => false,
            "message" => "Не удалось получить данные.",
            "data" => [],
         ]);
      }

      foreach ($about as $key => $value) {
         $value->pathOne = Storage::url('abouts/' . $value->imageOne);
         $value->pathTwo = Storage::url('abouts/' . $value->imageTwo);
         $value->pathThree = Storage::url('abouts/' . $value->imageThree);
      };

      return response()->json([
         "status" => true,
         "message" => "Успешно.",
         "data" => $about,
      ]);
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      ЦЕНЫ                         |*/
   /* |___________________________________________________|*/
   public function getPricesAll(Request $request) {
      $priceAddresses = PriceAddress::all();
      if (!$priceAddresses) {
         return response()->json([
            "status" => false,
            "message" => "Не удалось получить адреса.",
            "data" => null,
         ]);
      };
      
      $priceCategories = PriceCategory::all();
      if (!$priceCategories) {
         return response()->json([
            "status" => false,
            "message" => "Не удалось получить категории.",
            "data" => null,
         ]);
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
            "status" => false,
            "message" => "Не удалось получить услуги и цены.",
            "data" => null,
         ]);
      }

      return response()->json([
         "status" => true,
         "message" => "Успешно.",
         "data" => (object) [
            "adresses" => $priceAddresses,
            "categories" => $priceCategoriesFormat,
            "categoriesList" => $priceCategories,
            "prices" => $priceValues,
         ],
      ]);
   }
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
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                    КОНТАКТЫ                       |*/
   /* |___________________________________________________|*/
   public function getContactsAll(Request $request) {
      $clinics = Clinic::all();
      if(!$clinics) {
         return response()->json([
            "status" => false,
            "message" => "Не удалось получить контакты.",
            "data" => [],
         ]);
      };

      $contacts = Contact::all();
      if(!$contacts) {
         return response()->json([
            "status" => false,
            "message" => "Не удалось получить клиники.",
            "data" => [],
         ]);               
      };

      foreach ($contacts as $contactKey => $contactValue) {
         $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
         if(!$contactPhones) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось получить номера контакта.",
               "data" => [],
            ]);                  
         }

         $phones = [];
         foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
            $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
         };
         $contactValue->phones = $phones; 

         $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
         if(!$contactMails) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось получить почты контакта.",
               "data" => [],
            ]);                  
         }

         $mails = [];
         foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
            $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
         };
         $contactValue->mails = $mails; 
      };

      return response()->json([
         "status" => true,
         "message" => "Успешно.",
         "data" => (object) [
            "contacts" => $contacts,
            "clinics" => $clinics,
         ],
      ]);   
   }
   /* Получение контактов и клиник */
   public function getContactsClinicsAll(Request $request) {
      $contacts = Contact::all();
      if(!$contacts) {
         return response()->json([
            "status" => false,
            "message" => "Не удалось получить контакты.",
            "data" => [],
         ]);
      };

      foreach ($contacts as $contactKey => $contactValue) { 
         $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
         if(!$contactPhones) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось получить номера контакта.",
               "data" => [],
            ]);                  
         }

         $phones = [];
         foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
            $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
         };
         $contactValue->phones = $phones; 

         $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
         if(!$contactMails) {
            return response()->json([
               "status" => false,
               "message" => "Не удалось получить почты контакта.",
               "data" => [],
            ]);                  
         }

         $mails = [];
         foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
            $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
         };
         $contactValue->mails = $mails;         
         $contactValue->clinic = Clinic::where('id', $contactValue->clinicId)->first();
      }

      return response()->json([
         "status" => true,
         "message" => "Успешно.",
         "data" => $contacts,
      ]);   
   }
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                   РАСПИСАНИЕ                      |*/
   /* |___________________________________________________|*/
   /* Получение расписания */
   public function getShedulesAll(Request $request) {
      $shedules = Shedule::all();
      $sheduleClinics = ShedulesClinic::all();
      $currentDays = ShedulesCurrentDay::all();

      // Перебор массива с выбранными расписаниями
      foreach ($shedules as $sheduleKey => $sheduleValue) {
         $weeks = [];         
         
         // Перебор массива с выбранными клиниками 
         foreach ($sheduleClinics as $sheduleClinicsKey => $sheduleClinicsValue) {
            if ($sheduleClinicsValue->name === "Все") {
               continue;
            };

            $shedileClinicDays = ShedulesDay::where('sheduleId', $sheduleValue->id)->where('clinicId', $sheduleClinicsValue->id)->get();

            // Если есть дни
            if (count($shedileClinicDays) > 0) {
               $content = [];

               // Перебор массива с выбранными днями
               foreach ($currentDays as $currentDaysKey => $currentDaysValue) {
                  $status = false;

                  foreach ($shedileClinicDays as $shedileClinicDaysKey => $shedileClinicDaysValue) {
                     if ($currentDaysValue->date === $shedileClinicDaysValue->date) {
                        $times = [];
                        
                        $shedulesDaysTimes = ShedulesDaysTime::where('dayId', $shedileClinicDaysValue->id)->get();
                        foreach ($shedulesDaysTimes as $shedulesDaysTimesKey => $shedulesDaysTimesValue) {
                           $times[] = $shedulesDaysTimesValue->name;
                        };

                        $content[] = [
                           "id" => $currentDaysValue->id,
                           "date" => $shedileClinicDaysValue->date,
                           "time" => $times,
                        ];

                        $status = true;
                        if ($status) break;
                     };
                  };

                  if (!$status) {
                     $content[] = [
                        "id" => $currentDaysValue->id,
                        "date" => $currentDaysValue->date,
                        "time" => [],
                     ];
                  };
               };

               $weeks[] = (object) [
                  "clinicId" => $sheduleClinicsValue->id,
                  "status" => true,
                  "content" => $content,
               ];
            } else {
               $weeks[] = (object) [
                  "clinicId" => $sheduleClinicsValue->id,
                  "status" => false,
                  "content" => [],
               ];

            };

            $sheduleValue->weeks = $weeks;
         };
      };

      return response()->json([
         "status" => true,
         "message" => "Успешно!",
         "data" => (object) [
            "currentDays" => $currentDays,
            "shedules" => $shedules,
            "sheduleClinics" => $sheduleClinics,
         ],
      ]);
   }
};

function makeUrl($url) {
   $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($url);
   $stringUnderCase = strtolower($stringTransliterate);
   $stringReplace = str_replace(" ", "-", $stringUnderCase);

   return $stringReplace;
}

