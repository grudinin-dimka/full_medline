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

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
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
      $specialists = Specialist::all();
      foreach ($specialists as $key => $value) {
         $specialists[$key]->url = makeUrl($specialists[$key]->name);
         $specialists[$key]->path = Storage::url('specialists/' . $value->filename);      
      };

      return $specialists;
   }
   /* Вывод конкретного доктора */
   public function getSpecialistProfile(Request $request) {
      $specialists = Specialist::all();

      foreach ($specialists as $key => $value) {
         $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($specialists[$key]->name);
         $stringUnderCase = strtolower($stringTransliterate);
         $stringReplace = str_replace(" ", "-", $stringUnderCase);

         if ($request->url == $stringReplace) {
         $specialists[$key]->path= Storage::url('specialists/' . $specialists[$key]->filename);
         return $specialists[$key];
         };
      };
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
      $specialist = Specialist::where('id', $request->id)->first();
      $specialist->path = Storage::url('specialists/' . $specialist->filename);

      return response()->json([
         "specialist" => (object) [
            "profile" => $specialist,
            "connections" => (object) [
               "specializations" => SpecialistSpecialization::where('id_specialist', $request->id)->get(),
               "certificates" => SpecialistCertificate::where('id_specialist', $request->id)->get(),
               "clinics" => SpecialistClinic::where('id_specialist', $request->id)->get(),
               "educations" => SpecialistEducation::where('id_specialist', $request->id)->get(),
               "works" => SpecialistWork::where('id_specialist', $request->id)->get(),                  
            ],
         ],
         "sections" => (object) [
            "specializations" => Specialization::all(),
            "certificates" => Certificate::all(),
            "clinics" => Clinic::all(),
            "educations" => Education::all(),
            "works" => Work::all(),
         ],
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
      $specializations = Specialization::all();

      return response()->json([
         "educations" => $educations,
         "spezializations" => $specializations,
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
};

function makeUrl($url) {
   $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($url);
   $stringUnderCase = strtolower($stringTransliterate);
   $stringReplace = str_replace(" ", "-", $stringUnderCase);

   return $stringReplace;
}

