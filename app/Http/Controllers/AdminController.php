<?php
namespace App\Http\Controllers;

/* Подключения */
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/* Помощники */
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
Use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\File;

use Exception;

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
use App\Models\Education;
use App\Models\SpecialistEducation;
use App\Models\Work;
use App\Models\SpecialistWork;
use App\Models\Shedule;
use App\Models\ShedulesClinic;
use App\Models\ShedulesCurrentDay;
use App\Models\ShedulesDay;
use App\Models\ShedulesDaysTime;
use App\Models\PriceFile;
use App\Models\PriceAddress;
use App\Models\PriceCategory;
use App\Models\PriceValue;

/* Для работы с табличными файлами */
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Reader\IReader;
use \PhpOffice\PhpSpreadsheet\Reader\Ods;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

use function PHPUnit\Framework\isEmpty;

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
               File::types($request->formats)->max(10 * 1024),
            ],
         ]);
         if ($validated->fails()) return response()->json([
            "status" => false,
            "message" => "Файл не прошёл проверку.",
            "data" => null,
         ]);;

         $file = $request->file('image');
         $originalName = $file->getClientOriginalName();

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
            case 'abouts':
               $path = $request->file('image')->store(
                  'public/abouts'
               );
               break;
            case 'prices':
               $path = $request->file('image')->storeAs(
                  'public/prices',
                  $originalName, 
                  'local'
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
         "status" => true,
         "message" => "Данные обновлены.",
         "data" => $arrayID
      ]);
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
   /* |                  СПЕЦИАЛИСТЫ                      |*/
   /* |___________________________________________________|*/
   /* _____________________________________________________*/
   /* 1. Врачи                                             */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   /* Модульное сохранение */
   public function saveSpecialistModular(Request $request) {
      $result = null;
      
      switch ($request->type) {
         case "profile":
            $result = $this->saveSpecialistProfile($request);            
            return response()->json($result);
            break;         
         case "specializations":
            $result = $this->saveSpecialistSpecializations($request);
            return response()->json($result);
         case "clinics":
            $result = $this->saveSpecialistClinics($request);
            return response()->json($result);
            break;         
         case "certificates":
            $result = $this->saveSpecialistCertificates($request);
            return response()->json($result);
            break;         
         case "educations":
            $result = $this->saveSpecialistEducations($request);
            return response()->json($result);
            break;         
         case "works":
            $result = $this->saveSpecialistWorks($request);
            return response()->json($result);
            break;
         case "all":
            // Сохранение профиля
            $saveProfile = $this->saveSpecialistProfile($request);            
            if (!$saveProfile->status) {
               return response()->json($saveProfile);
            };
            // Сохранение специализаций
            $saveSpecializations = $this->saveSpecialistSpecializations($request);            
            if (!$saveSpecializations->status) {
               return response()->json($saveSpecializations);
            };
            // Сохранение клиник
            $saveClinic = $this->saveSpecialistClinics($request);            
            if (!$saveClinic->status) {
               return response()->json($saveClinic);
            };
            // Сохранение сертификатов
            $saveClinic = $this->saveSpecialistCertificates($request);            
            if (!$saveClinic->status) {
               return response()->json($saveClinic);
            };
            // Сохранение образований
            $saveEducation = $this->saveSpecialistEducations($request);            
            if (!$saveEducation->status) {
               return response()->json($saveEducation);
            };
            // Сохранение работ
            $saveWork = $this->saveSpecialistWorks($request);            
            if (!$saveWork->status) {
               return response()->json($saveWork);
            };

            return response()->json([
               "status" => true,
               "message" => "Все данные специалиста сохранены.",
               "data" => (object) [
                  "imagePath" => $saveProfile->data,
                  "certificates" => $saveClinic->data,
                  "educations" => $saveEducation->data,
                  "works" => $saveWork->data,     
               ],
            ]);
            break;
         default:
            return response()->json([
               "status" => false,
               "message" => "Неизивестный тип сохранения.",
               "data" => null,
            ]);            
            break;
      }
   }
   /* Модуль: Сохранение профиля */ 
   public function saveSpecialistProfile(Request $request) {
      $profile = json_decode($request->profile);
      
      $path = null;
      if($request->hasFile('image')) {
         $validated = validator($request->all(), [
            'image' => [
               'required',
               File::types(['png', 'webp'])->max(10 * 1024),
            ],
         ]);

         if ($validated->fails()) return (object) [
            "status" => false,
            "message" => "Файл не прошёл проверку.",
            "data" => null,
         ];

         $path = $request->file('image')->store(
            'public/specialists'
         );
         
         if (!$path) {
            return (object) [
               "status" => false,
               "message" => "Не удалось сохранить изображение.",
               "data" => null,
            ];
         }
      };      

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
            'childrenDoctorAge' => $profile->childrenDoctor->body ? $profile->childrenDoctorAge->body : 0,
         ]);

         if($path) $specialist->update(['filename' => str_replace("public/specialists/", "", $path)]);

         return (object) [
            "status" => true,
            "message" => "Данные о профиле специалиста обновлены.",
            "data" => $path ? Storage::url($path) : null,
         ];   
      } else {
         return (object) [
            "status" => false,
            "message" => "Специалист не найден.",
            "data" => null,
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
            
            return (object) [
               "status" => true,
               "message" => "Данные о специализациях сохранились.",
               "data" => null,
            ];
         } else {
            return (object) [
               "status" => false,
               "message" => "При поиске данных о специализациях возникли проблемы.",
               "data" => null,
            ];
         };
      } else {
         return (object) [
            "status" => false,
            "message" => "Специалист не найден.",
            "data" => null,
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
         foreach ($specialistClinics as $key => $value) {
            // Поиск в массиве
            $status = false;
            foreach ($clinics as $key => $valueReqArr) {
               if (
                  $value->id_specialist === $valueReqArr->id_specialist 
                     && 
                  $value->id_clinic === $valueReqArr->id_clinic
                     &&
                  $value->priem === $valueReqArr->priem
               ) {
                  $status = true;
               };
            }

            if (!$status) {
               $value->delete();
            };
         };
         
         return (object) [
            "status" => true,
            "message" => "Данные о клиниках сохранились.",
         ];
      } else {
         return (object) [
            "status" => false,
            "message" => "Специалист не найден.",
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
               return (object) [
                  "status" => false,
                  "message" => "Не удалось обновить значение.",
               ];      
            }
         };
         return (object) [
            "status" => true,
            "message" => "Данные о сертификатах сохранились.",
            "data" => $arrayID,
         ];
      } else {
         return (object) [
            "status" => false,
            "message" => "Специалист не найден.",
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
               return (object) [
                  "status" => false,
                  "message" => "Не удалось обновить значение.",
                  "data" => null,
               ];      
            }
         }

         return (object) [
            "status" => true,
            "message" => "Данные об образованиях сохранились.",
            "data" => $arrayID,
         ];
      } else {
         return (object) [
            "status" => false,
            "message" => "Специалист не найден.",
            "data" => null,
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
               return (object) [
                  "status" => false,
                  "message" => "Не удалось обновить значение.",
                  "data" => null,
               ];      
            }
         }

         return (object) [
            "status" => true,
            "message" => "Данные о прошлых работах сохранились.",
            "data" => $arrayID,
         ];
      } else {
         return (object) [
            "status" => false,
            "message" => "Специалист не найден.",
            "data" => null,
         ];
      }      
   }
   /* Сохранение таблицы со специалистами */ 
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
      } else {
         return response()->json([
            "status" => false,
            "message" => "Отсутствуют файлы.",
            "data" => null,
         ]);
      }

      return response()->json([
         "status" => true,
         "message" => "Данные обновлены.",
         "data" => null,
      ]);
   }
   /* Добавление нового специалиста */ 
   public function addSpecialist(Request $request) {
      $path = null;

      if($request->hasFile('image')) {
         $validated = validator($request->all(), [
            'image' => [
               'required',
               File::types(['png', 'webp'])->max(10 * 1024),
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
         "adultDoctor" => $profile->adultDoctor->body,
         "childrenDoctor" => $profile->childrenDoctor->body,
         "childrenDoctorAge" => $profile->childrenDoctor->body ? $profile->childrenDoctorAge->body : 0,
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
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                   РАСПИСАНИЕ                      |*/
   /* |___________________________________________________|*/
   /* Сохранение расписания */
   public function saveShedulesAll() {
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
         
         $currentFileDate = Carbon::createFromFormat('Y-m-d', $CurrentFileName);
         $lastFileDate = Carbon::createFromFormat('Y-m-d', $dateLastFileName);

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
            Log::error('Отсутствуют клиники.');
            return response()->json(['error' => 'Отсутствуют клиники.']);      
         }

         $week = $fileContent?->week;
         if (!$week) {
            Log::error('Отсутствуют дни.');
            return response()->json(['error' => 'Отсутствуют дни.']);      
         }

         $shedules = $fileContent?->shedules;
         if (!$shedules) {
            Log::error('Отсутствует расписание.');
            return response()->json(['error' => 'Отсутствуют расписание.']);      
         }
      } else {
         Log::error('Отсутствует файл.');
         return response()->json(['error' => 'Отсутствует файл.']);
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
            "status" => true,
            "message" => "Цен нет.",
            "data" => [],
         ]);
      } else {
         foreach ($pricesFiles as $pricesFilesKey => $pricesFilesValue) {
            $pricesFiles[$pricesFilesKey]->path = Storage::url('prices/' . $pricesFilesValue->filename);
         };

         return response()->json([
            "status" => true,
            "message" => "Цены успешно получены.",
            "data" => $pricesFiles,
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

            if (!$dataFromFile->status) {
               return response()->json([
                  "status" => false,
                  "message" => $dataFromFile->message,
                  "data" => $arrayID,
               ]);
            }; 

            // Перебор полученных массивов            
            foreach ($dataFromFile->data as $dataFromFileKey => $dataFromFileValue) { 
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
                           "status" => false,
                           "message" => "Название адреса в файле " . basename(Storage::path($filesPricesValue)) . " не может быть пустым.",
                           "data" => $arrayID,
                        ]) ;
                     };
                     
                     $currentAddress = PriceAddress::create([
                        "name" => $dataValue[0],
                     ]);
                  }
                  
                  // Заполнение данных из таблицы 
                  if (($index + 1) >= 16) {
                     if (count($dataValue) < 2) { 
                        return response()->json([
                           "status" => false,
                           "message" => "Недостаточно данных (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                           "data" => $arrayID,
                        ]);
                     };

                     $levelClear = trim($dataValue[0], " ");                        

                     // Проверка на категорию
                     if (str_contains($levelClear, '#')) {
                        if (empty($dataValue[1])) {
                              return response()->json([
                              "status" => false,
                              "message" => "Название категории (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . " не может быть пустым.",
                              "data" => $arrayID,
                           ]);
                        };

                        // Проверка на пустую общую категорию
                        if (strlen($levelClear) > 1 && $currentCategory === null) {
                           return response()->json([
                              "status" => false,
                              "message" => "Нельзя создать подкатегорию без общей категории (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "data" => $arrayID,
                           ]);                                 
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
                              "status" => false,
                              "message" => "Недостаточно данных (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "data" => $arrayID,
                           ]);
                        };

                        if (!is_numeric($dataValue[2])) {
                           return response()->json([
                              "status" => false,
                              "message" => "Цена (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . " должна быть числом.",
                              "data" => $arrayID,
                           ]);
                        };
                        
                        $createPriceValue = PriceValue::create([
                           "name" => $dataValue[1],
                           "price" => $dataValue[2],
                           "categoryId" => $currentCategory->id,
                        ]);

                        if (!$createPriceValue) {
                           return response()->json([
                              "status" => false,
                              "message" => "Не удалось создать цену (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "data" => $arrayID,
                           ]);
                        };
                     };
                  };

                  $index++;
               };
            };
         };   
      };

      return response()->json([
         "status" => true,
         "message" => "Цены успешно сохранены.",
         "data" => count($arrayID) > 0 ? $arrayID : null,
      ]);            
   }
   /* Считывание данных с файла .ods, .xls, .xlsx */
   protected function getDataFromFile($path) {
      try {
         // Существует ли файл
         if (!file_exists($path)) {
            return (object) [
               "status" => false,
               "message" => "Файл '" . basename($path) . "' не существует.",
               "data" => null,
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
               return (object) [
                  "status" => false,
                  "message" => "Раздел '" . $sheet->getTitle() . "' в файле '" . basename($path) . "' пустой.",
                  "data" => null,
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

         return (object) [
            "status" => true,
            "message" => "Данные с файла считаны.",
            "data" => $sheetsAlldata,
         ];
      } catch (Exception $e) {
         return (object) [
            "status" => false,
            "message" => "Не удалось считать данные.",
            "data" => null,
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
}
