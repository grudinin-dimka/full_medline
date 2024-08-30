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
  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |              СЛАЙДЕР              |*/
  /* |___________________________________|*/
  /* _____________________________________*/
  /* 1.      Сохранение, удаление         */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Сохранение содержимого всех слайдов
  public function saveSlidesChanges(Request $request) {
    // /* Изменение содержимого слайдов */
    // foreach ($request->slides as $key => $value) {
    //   if ($value['delete'] === true) {
    //     $slide = Slide::where('order', $value['order'])->first();
    //     $slide->delete();
    //   } else if ($value['create'] === true){
    //     $slideCreate = Slide::create([
    //       'name' => $value['name'],
    //       'link' => $value['link'],
    //       'filename' => $value['filename'],
    //       'hide' => $value['hide'],
    //       'order' => $value['order'],
    //     ]);
    //   } else {
    //     $slide = Slide::where('order', $value['order'])->first();
    //     $slideUpdate = $slide->update([
    //       'name' => $value['name'],
    //       'link' => $value['link'],
    //       'filename' => $value['filename'],
    //       'hide' => $value['hide'],
    //       'order' => $value['order'],
    //     ]);  
    //   };
    // };

    $slides = Slide::all()->SortBy('order');
    var_dump(count($slides));
    var_dump($slides[5]);

    foreach ($slides as $slideKey => $slideValue) {
      // var_dump($slideValue);
    };

    // /* Удаление неиспользуемых файлов */
    // $files = Storage::allFiles('public');
    // foreach ($files as $fileKey => $fileValue) {
    //   $fileUses = false;
    //   foreach ($slides as $slideKey => $slideValue) {
    //     if ($slideValue->filename == substr($fileValue, 7)) {
    //       $fileUses = true;
    //     };
    //   };

    //   if (!$fileUses) {
    //     Storage::delete($fileValue);
    //   }
    // };
  } 
  // Загрузка файла на сервер 
  public function uploadFile(Request $request) {
		/* Проверка на наличие переменной image с файлом в запросе */  
		if($request->hasFile('image')) {
      $path = $request->file('image')->store(
          './',
          'public'
      );
    };
    return Storage::url(substr($path, 3));
	} 

  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |               ФУТЕР               |*/
  /* |___________________________________|*/
  /* _____________________________________*/
  /* 1.       Сохранение, очистка         */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Сохранение футера 
  public function saveFooter(Request $request) {
    $footer = Footer::find(1);
    $footerUpdate = $footer->update([
      'title' => $request->title,
      'titleDesc' => $request->titleDesc,
      'license' => $request->license,
      'licenseDesc' => $request->licenseDesc,
      'footer' => $request->footer,
    ]);
    
    /* Проверка обновления футера */
    if ($footerUpdate) {
      return true;
    } else {
      return false;
    };
  }

  // Очистка футера 
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
