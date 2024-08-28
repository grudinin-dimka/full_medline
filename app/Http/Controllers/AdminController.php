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
use App\Models\User;
use App\Models\Slide;
use App\Models\Footer;

class AdminController extends Controller
{
  /*-----------------------------------------*/
  /*-----------Изменение слайдера------------*/
  /*-----------------------------------------*/
  /* Изменение состояние слайда  */
  public function saveSlidesChanges(Request $request) {
    foreach ($request->slides as $key => $value) {
      $slide = Slide::find($value['id']);
      $slideUpdate = $slide->update([
        'name' => $value['name'],
        'path' => $value['path'],
        'hide' => $value['hide'],
        'order' => $value['order'],
      ]);
    }
    return "Данные обновлены.";
  } 

  /* Изменение состояние слайда */ 
  	public function uploadFile(Request $request) {
		// Проверка на наличие переменной image с файлом в запросе 
		if($request->hasFile('image')) {
      $path = $request->file('image')->storeAs(
          'img',
          'testname.png',
          'public'
      );
    };
    return $path;
	} 

  /*-----------------------------------------*/
  /*------------Изменение футера-------------*/
  /*-----------------------------------------*/
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

  /* Очистка футера */ 
  public function clearFooter(Request $request) {
    $footer = Footer::find(1);
    $footer->update([
      'title' => '',
      'titleDesc' => '',
      'license' => '',
      'licenseDesc' => '',
      'footer' => '',
    ]);
    
    return 'Футер очищен.';
  } 
}
