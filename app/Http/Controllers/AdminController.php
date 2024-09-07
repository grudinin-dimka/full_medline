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
use Illuminate\Validation\Rules\File;

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
    /* Удаление слайдов, подлежащих удалению */
    foreach ($request->slides as $key => $value) {
      if ($value['delete'] === true) {
        $slide = Slide::find($value['id']);
        $slide->delete();
      };
    };

    /* Добавление новых слайдов */
    foreach ($request->slides as $key => $value) {
      if ($value['create'] === true){
        $slideCreate = Slide::create([
          'name' => $value['name'],
          'link' => $value['link'],
          'filename' => $value['filename'],
          'hide' => $value['hide'],
          'order' => $value['order'],
        ]);
      };
    };

    /* Обновление существующих слайдов */
    foreach ($request->slides as $key => $value) {
      if ($value['delete'] !== true && $value['create'] !== true) {
        $slide = Slide::find($value['id']);
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
    $filesSlides = Storage::files('public/slides');

    foreach ($filesSlides as $fileKey => $fileValue) {
      $useFile = false;
      // Проверка на использование файла
      foreach ($slides as $slideKey => $slideValue) {
        // Обрезание значения $fileValue до названия файла
        $str = str_replace('public/slides/', '', $fileValue);
        // Проверка значения названия файла на совпадение
        if ($slideValue->filename == $str) {
          $useFile = true;
        };
      };

      // Удаление файла, если не используется
      if (!$useFile) {
        Storage::delete($fileValue);
      };
    };

    return true;
  } 
  // Загрузка файла на сервер 
  public function uploadFile(Request $request) {
		/* Проверка на наличие переменной image с файлом в запросе */  
		if($request->hasFile('image')) {
      $validated = validator($request->all(), [
        'image' => [
          'required',
          File::types('png')->max(10 * 1024),
        ],
      ]);
      if ($validated->fails()) return false;

      switch ($request->type) {
        case 'slide':
          $path = $request->file('image')->store(
            'public/slides'
          );
          break;
        case 'doctor':
          $path = $request->file('image')->store(
            'public/doctor'
          );
          break;
      }
    };
    return Storage::url($path);
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
