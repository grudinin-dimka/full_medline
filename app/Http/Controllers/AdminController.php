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
use App\Models\Doctor;

class AdminController extends Controller
{
  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |                    СЛАЙДЕР                        |*/
  /* |___________________________________________________|*/
  /* _____________________________________________________*/
  /* 1. Сохранение, удаление                              */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
  // Сохранение содержимого всех слайдов
  public function saveSlidesChanges(Request $request) {
    /* Изменение содержимого слайдов */
    foreach ($request->slides as $key => $value) {
      if ($value['delete'] === true) {
        $slide = Slide::where('order', $value['order'])->first();
        $slide->delete();
      } else if ($value['create'] === true){
        $slideCreate = Slide::create([
          'name' => $value['name'],
          'link' => $value['link'],
          'filename' => $value['filename'],
          'hide' => $value['hide'],
          'order' => $value['order'],
        ]);
      } else {
        $slide = Slide::where('order', $value['order'])->first();
        $slideUpdate = $slide->update([
          'name' => $value['name'],
          'link' => $value['link'],
          'filename' => $value['filename'],
          'hide' => $value['hide'],
          'order' => $value['order'],
        ]);  
      };
    };

    /* Сортировка слайдов по порядку от наименьшего до наибольшого */
    $slides = Slide::all()->SortBy('order');

    /* Обновление порядковых номеров */
    $count = 0;
    foreach ($slides as $slideKey => $slideValue) {
      $count++;
      $slideValue->order = $count;
      $slideValue->save();
    };

    /* Получение всех файлов */
    $filesAll = Storage::files('public');

    /* Объявление массива с префиксом s- */
    $filesUses = [];
    foreach ($filesAll as $fileKey => $fileValue) {
      $fileName = substr($fileValue, 7);
      // Проверка на наличие префикса
      if (substr($fileName, 0, 2) == 's-') {
        array_push($filesUses, $fileName);
        var_dump("Файл с префиксом: " . $fileName);
      }
    };

    foreach ($filesUses as $fileKey => $fileValue) {
      $useFile = false;
      // Проверка на использование файла
      foreach ($slides as $slideKey => $slideValue) {
        if ($slideValue->filename == $fileValue) {
          $useFile = true;
        };
      };

      // Удаление файла, если не используется
      if (!$useFile) {
        Storage::delete('public/' . $fileValue);
      }
      var_dump($useFile);
    };
  } 
  // Загрузка файла на сервер 
  public function uploadFile(Request $request) {
		/* Проверка на наличие переменной image с файлом в запросе */  
		if($request->hasFile('image')) {
      $path = $request->file('image')->storeAs(
        './',
        's-' . $request->filename,
        'public',
      );
    };
    return Storage::url(substr($path, 3));
  } 

  /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
  /* |                     ФУТЕР                         |*/
  /* |___________________________________________________|*/
  /* _____________________________________________________*/
  /* 1. Сохранение, удаление                              */
  /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
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
