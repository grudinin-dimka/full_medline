<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Slide;
use App\Models\Footer;
use App\Models\Doctor;

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |              СЛАЙДЕР              |*/
  /* |___________________________________|*/
  /* _____________________________________*/
  /* 1.        Получение данных           */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Получение данных о всех слайдах
  public function getSlidesAll(Request $request) {
    $slides = Slide::all();
    foreach ($slides as $key => $value) {
      $slides[$key]->path = Storage::url($value->filename);
    };
    return $slides;
  } 
  // Получение данных о всех слайдах, которые не скрыты
  public function getSlidesNotHide(Request $request) {
    $slides = Slide::where('hide', false)->get();
    foreach ($slides as $key => $value) {
      $slides[$key]->path = Storage::url($value->filename);
    };
    return $slides;
  }
  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |               ФУТЕР               |*/
  /* |___________________________________|*/
  /* _____________________________________*/
  /* 1.        Получение данных           */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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

  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |              Доктора              |*/
  /* |___________________________________|*/
  /* _____________________________________*/
  /* 1.      Вывод данных на фронт        */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Вывод всех докторов 
  public function getDoctors(Request $request) {
    $doctors = Doctor::all();
    return $doctors;
  }
  // Вывод конкретного доктора
  public function getDoctorProfile(Request $request) {
    $doctors = Doctor::all();
    return $doctors;
  }
};
