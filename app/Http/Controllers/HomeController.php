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
   /* 1. Получение данных                                  */
   /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
   // Вывод всех докторов 
   public function getSpecialists(Request $request) {
      $specialists = Specialist::all();
      foreach ($specialists as $key => $value) {
         $specialists[$key]->url = makeUrl($specialists[$key]->name);
         $specialists[$key]->path = Storage::url('specialists/' . $value->filename);      
      };

      return $specialists;
   }

   // Вывод конкретного доктора
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

   public function getSpecialistAll(Request $request) {
      $specialist = Specialist::where('id', $request->id)->first();

      return $specialist;
   }

   // Вывод сокращенной информации о врачах 
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
};

function makeUrl($url) {
   $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($url);
   $stringUnderCase = strtolower($stringTransliterate);
   $stringReplace = str_replace(" ", "-", $stringUnderCase);

   return $stringReplace;
}

