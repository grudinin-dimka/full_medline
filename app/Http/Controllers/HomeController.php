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

class HomeController extends Controller
{
  /*-----------------------------------------*/
  /*-------------Вывод значений--------------*/
  /*-----------------------------------------*/
  public function getSlides(Request $request) {
    $slides = Slide::all();
    return $slides;
  } 

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

  

}
