<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

/* Ошибки */
use Throwable;

/* Помощники */
use Carbon\Carbon;
use Transliterator;
use ZipArchive;

/* Excel */
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use XMLWriter;

/* Модели */
use App\Models\Prices\Price;
use App\Models\Prices\PriceAddress;
use App\Models\Prices\PriceCategory;
use App\Models\Prices\PriceFile;
use App\Models\Prices\PriceValue;

class PricesController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   /* Вывод адресов с ценами */
   public function getPricesChoice(Request $request)
   {
      $priceAddresses = PriceAddress::all();

      $cityFlags = array("г. ", "город", "р.п.", "р. п.", "п.", "п. ");
      $streetFlags = array("ул. ", "улица ");
      $houseFlags = array("д.", "д. ", "дом", "дом ",);

      $formatPriceAddresses = [];
      foreach ($priceAddresses as $key => $value) {
         $splitAddress = explode(', ', $value->name);

         trim($splitAddress[0]);

         $city = trim(str_ireplace($cityFlags, "", $splitAddress[0]), " ");
         $street = trim(str_ireplace($streetFlags, "", $splitAddress[1]), " ");
         $house = trim(str_ireplace($houseFlags, "", $splitAddress[2]), " ");

         $formatPriceAddresses[] = (object) [
            "city" => $city,
            "cityUrl" => $this->makeUrl($city),
            "street" => $street,
            "streetUrl" => $this->makeUrl($street),
            "house" => $house,
            "houseUrl" => $this->makeUrl($house),
         ];
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Адреса успешно получены.",
         "result" => $formatPriceAddresses,
      ], 200);
   }

   /* Вывод шаблона цен */
   public function getPricesTemplate(Request $request)
   {
      // Валидация
      $validated = Validator::make($request->all(), [
         'city' => 'required',
         'street' => 'required',
         'house' => 'required',
      ], [
         'city.required' => 'Отсутствует город.',
         'street.required' => 'Отсутствует улица.',
         'house.required' => 'Отсутствует номер дома.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $priceAddresses = PriceAddress::all();

      // Флаги для поиска
      $cityFlags = array("г. ", "город", "р.п.", "р. п.", "п.", "п. ");
      $streetFlags = array("ул. ", "улица ");
      $houseFlags = array("д.", "д. ", "дом", "дом ",);

      // Текущий адрес
      $currentAddress = null;

      // Все адреса
      $addresses = [];

      try {
         // Формирование ссылок на клиники в заголовке
         foreach ($priceAddresses as $key => $value) {
            $splitAddress = explode(', ', $value->name);

            $city = trim(str_ireplace($cityFlags, "", $splitAddress[0]), " ");
            $street = trim(str_ireplace($streetFlags, "", $splitAddress[1]), " ");
            $house = trim(str_ireplace($houseFlags, "", $splitAddress[2]), " ");

            if ($this->makeUrl($city) != $request->city) {
               continue;
            };

            if ($this->makeUrl($street) != $request->street) {
               continue;
            };

            if ($this->makeUrl($house) != $request->house) {
               continue;
            };

            $currentAddress = $value;
            break;
         };

         $categories = PriceCategory::where('addressId', $currentAddress->id)->get();

         // Форматируем категории
         $categoriesFormat = [];
         foreach ($categories as $categoriesKey => $categoriesValue) {
            if ($categoriesValue->categoryId != null) continue;

            $categoriesFormat[] = $this->getCategoryArray($categoriesValue);
         }

         // Получаем ID категорий
         $categoryIds = $categories->pluck('id');

         // Получаем цены, привязанные к этим категориям
         $prices = PriceValue::whereIn('categoryId', $categoryIds)->get();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Адрес успешно получен.",
            "result" => (object) [
               "address" => $currentAddress,
               "categories" => $categoriesFormat,
               "categoriesList" => $categories,
               "prices" => $prices
            ],
         ], 200);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не получилось получить данные.",
            "result" => null,
         ], 500);
      }
   }

   /* Форматирование категорий */
   public function getCategoryArray($category)
   {
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

   /* Вывод шаблона цен */
   public function getPricesComplecte(Request $request)
   {
      try {
         // Адресса
         $addresses = PriceAddress::all();

         // Категории
         $prices = PriceValue::all();
         $pricesCategorys = $prices->pluck('categoryId');

         $categories = PriceCategory::all()->whereIn('id', $pricesCategorys);

         // Текущий адрес
         $currentAddress = PriceAddress::where('name', 'like', '%Комсомольская%')->first();

         if (!isset($currentAddress)) {
            $currentAddress = PriceAddress::all()->first();
         };

         $categories = PriceCategory::where('addressId', $currentAddress->id)->get();

         // Получаем ID категорий
         $categoryIds = $categories->pluck('id');

         $prices = PriceValue::whereIn('categoryId', $categoryIds)->get();

         $pricesCategories = $prices->pluck('categoryId');
         $categories = PriceCategory::whereIn('id', $pricesCategories)->get();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Адрес успешно получен.",
            "result" => [
               "addresses" => $addresses,
               // "uniqueCategories" => $uniqueCategories,

               "address" => $currentAddress,
               "categories" => $categories,
               "prices" => $prices
            ],
         ], 200);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не получилось получить данные.",
            "result" => null,
         ], 500);
      }
   }

   /* Вывод шаблона цен */
   public function getPricesManual(Request $request)
   {
      // Валидация
      $validated = Validator::make($request->all(), [
         'address' => 'nullable',
         'category' => 'nullable',
      ], [
         'address.required' => 'Отсутствует адресс.',
         'category.required' => 'Отсутствует категория.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      try {
         // Проверка на существование адреса
         if (isset($request->address)) {
            $currentAddress = PriceAddress::where('name', $request->address)->first();
         } else {
            $currentAddress = PriceAddress::all()->first();
         }

         // Проверка на существование категории
         if (isset($request->category)) {
            $categoriesList = PriceCategory::where('addressId', $currentAddress->id)
               ->where('name', $request->category)
               ->get();
         } else {
            $categoriesList = PriceCategory::where('addressId', $currentAddress->id)->get();
         };

         // Получаем ID категорий
         $categoryIds = $categoriesList->pluck('id');

         // Получаем цены, привязанные к этим категориям
         if (isset($request->price)) {
            $prices = PriceValue::whereIn('categoryId', $categoryIds)->where('name', 'like', '%' . $request->price . '%')->get();
         } else {
            $prices = PriceValue::whereIn('categoryId', $categoryIds)->get();
         };

         $pricesCategories = $prices->pluck('categoryId');

         $categoriesListAll = PriceCategory::where('addressId', $currentAddress->id)->get();
         $pricesAddress = PriceValue::whereIn('categoryId', $categoriesListAll->pluck('id'))->get();
         $pricesAddressCategories = $pricesAddress->pluck('categoryId');
         $categoriesAddress = PriceCategory::whereIn('id', $pricesAddressCategories)->get();

         $categoriesList = PriceCategory::whereIn('id', $pricesCategories)->get();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Адрес успешно получен.",
            "result" => [
               "address" => $currentAddress,
               "categories" => $categoriesAddress,
               "categoriesList" => $categoriesList,
               "prices" => $prices
            ],
         ], 200);
      } catch (Throwable $th) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не получилось получить данные.",
            "result" => null,
         ], 500);
      }
   }

   /* Получение списка адресов */
   public function getPricesAddressesList(Request $request)
   {
      try {
         $addresses = PriceAddress::all();

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $addresses,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Произошла ошибка.",
            "result" => null,
         ], 500);
      };
   }

   /* Получение списка категорий */
   public function getPricesCategoriesList(Request $request)
   {
      try {
         $prices = PriceValue::all();
         $pricesCategorys = $prices->pluck('categoryId');

         $categories = PriceCategory::all()->whereIn('id', $pricesCategorys);

         $uniqueCategories = [];

         foreach ($categories as $categoryKey => $categoryValue) {
            foreach ($uniqueCategories as $uniqueCategoriesKey => $uniqueCategoriesValue) {
               if ($uniqueCategoriesValue->name == $categoryValue->name) {
                  continue 2;
               };
            };

            $uniqueCategories[] = $categoryValue;
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $uniqueCategories,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Произошла ошибка.",
            "result" => null,
         ], 500);
      };
   }

   /* Получение групп цен */
   public function getPricesGruop(Request $request)
   {
      // Валидация
      $validated = Validator::make($request->all(), [
         'group' => 'required',
      ], [
         'required' => 'Поле :attribute обязательно для заполнения.',
      ]);

      if ($validated->fails()) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "errors" => $validated->errors(),
            "message" => "Некорректные данные.",
            "result" => null,
         ], 422);
      };

      $group = $request->group;
      $title = "";
      $array = [];

      switch ($group) {
         case 'travels':
            $title = "Путевки";

            // Получение всех адресов
            $addresses = PriceAddress::all();

            // Перебор адресов
            foreach ($addresses as $addressesKey => $addressesValue) {
               $categories = [];

               // Поиск нужных категорий
               $categories = PriceCategory::where('addressId', '=', $addressesValue->id)
                  ->where(function ($query) {
                     $query->where('name', 'like', '%Путевки%')
                        ->orWhere('name', 'like', '%путевки%')
                        ->orWhere('name', 'like', '%Путёвки%')
                        ->orWhere('name', 'like', '%путёвки%')
                        ->orWhere('name', 'like', '%Комплексные программы%');
                  })->get();

               // Проверка на наличие категорий
               if (count($categories) > 0) {
                  $categoriesIds = $categories->pluck('id')->toArray();

                  $prices = PriceValue::whereIn('categoryId', $categoriesIds)->get()->groupBy('categoryId');

                  // Проверяем наличие цен
                  foreach ($categories as $categoryKey => $categoryValue) {
                     $categoryValue->prices = $prices[$categoryValue->id];
                  }

                  $array[] = [
                     "id" => $addressesValue->id,
                     "name" => $addressesValue->name,
                     "categories" => $categories,
                  ];
               }
            }
            break;
         case 'plastic':
            $title = "Пластика";

            // Получение всех цен, которые имеют вхождения по названию
            $prices = PriceValue::where(function ($query) {
               $query->where('name', 'like', '%Блефаропластика%')
                  ->orWhere('name', 'like', '%блефаропластика%')
                  ->orWhere('name', 'like', '%Булхорн%')
                  ->orWhere('name', 'like', '%булхорн%');
            })->get()->groupBy('categoryId');

            // Получение id всех категорий, где есть такие цены
            $categoryIds = $prices->keys()->toArray();

            // Получение всех категорий
            $categories = PriceCategory::whereIn('id', $categoryIds)->get()->groupBy('addressId');

            // Получение id всех категорий, где есть такие цены
            $addressesIds = $categories->keys()->toArray();
            $addresses = PriceAddress::whereIn('id', $addressesIds)->get();

            foreach ($addresses as $addressesKey => $addressesValue) {
               $categories = $categories[$addressesValue->id];

               foreach ($categories as $categoryKey => $categoryValue) {
                  $categoryValue->prices = $prices[$categoryValue->id];
               };

               $array[] = [
                  "id" => $addressesValue->id,
                  "name" => $addressesValue->name,
                  "categories" => $categories,
               ];
            };
            break;
         default:
            $title = "Не найдено";

            $categories = [];
            break;
      }

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => [
            "array" => $array ?? [],
            "title" => $title
         ],
      ], 200);
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   /* Получение всех файлов с ценами */
   public function getPricesFilesAll(Request $request)
   {
      $pricesFiles = PriceFile::all();


      foreach ($pricesFiles as $pricesFilesKey => $pricesFilesValue) {
         $pricesFiles[$pricesFilesKey]->path = Storage::url('prices/' . $pricesFilesValue->filename);
         $pricesFiles[$pricesFilesKey]->date = $pricesFilesValue->created_at;
      };

      return response()->json([
         "success" => true,
         "debug" => false,
         "message" => "Данные получены.",
         "result" => $pricesFiles,
      ]);
   }

   /* Сохранение изменений */
   public function savePricesChanges(Request $request)
   {
      $generateCategories = [];
      $pricesFiles = json_decode($request->pricesFiles);
      $arrayID = [];

      foreach ($pricesFiles as $key => $value) {
         // Удаление
         if ($value->delete === true) {
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
      if ($filesPrices) {
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

            if (!$dataFromFile['object']->success) {
               return response()->json($dataFromFile['object'], $dataFromFile['status']);
            };

            // Перебор полученных массивов            
            foreach ($dataFromFile['object']->result as $dataFromFileKey => $dataFromFileValue) {
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
                           "success" => false,
                           "debug" => true,
                           "message" => "Название адреса в файле " . basename(Storage::path($filesPricesValue)) . " не может быть пустым.",
                           "result" => $arrayID,
                        ], 422);
                     };

                     $currentAddress = PriceAddress::create([
                        "name" => trim($dataValue[0], " "),
                     ]);
                  }

                  // Заполнение данных из таблицы 
                  if (($index + 1) >= 16) {
                     if (count($dataValue) < 2) {
                        return response()->json([
                           "success" => false,
                           "debug" => true,
                           "message" => "Недостаточно данных (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                           "result" => $arrayID,
                        ], 422);
                     };

                     $levelClear = trim($dataValue[0], " ");

                     // Проверка на категорию
                     if (str_contains($levelClear, '#')) {
                        if (empty($dataValue[1])) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Название категории (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . " не может быть пустым.",
                              "result" => $arrayID,
                           ], 422);
                        };

                        // Проверка на пустую общую категорию
                        if (strlen($levelClear) > 1 && $currentCategory === null) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Нельзя создать подкатегорию без общей категории (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "result" => $arrayID,
                           ], 422);
                        };

                        // Создание новой категории
                        $currentCategory = PriceCategory::create([
                           "name" => trim($dataValue[1], " "),
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
                              "success" => false,
                              "debug" => true,
                              "message" => "Недостаточно данных (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "result" => $arrayID,
                           ], 422);
                        };

                        if (!is_numeric($dataValue[2])) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Цена (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . " должна быть числом.",
                              "result" => $arrayID,
                           ], 422);
                        };

                        $createPriceValue = PriceValue::create([
                           "name" => trim($dataValue[1], " "),
                           "price" => $dataValue[2],
                           "categoryId" => $currentCategory->id,
                        ]);

                        if (!$createPriceValue) {
                           return response()->json([
                              "success" => false,
                              "debug" => true,
                              "message" => "Не удалось создать цену (строка " . ($index + 1) . ") в файле " . basename(Storage::path($filesPricesValue)) . ".",
                              "result" => $arrayID,
                           ], 422);
                        };
                     };
                  };

                  $index++;
               };
            };
         };
      };

      return response()->json([
         "success" => true,
         "debug" => true,
         "message" => "Цены успешно сохранены.",
         "result" => count($arrayID) > 0 ? $arrayID : null,
      ], 200);
   }

   /* Считывание данных с файла .ods, .xls, .xlsx */
   protected function getDataFromFile($path)
   {
      try {
         // Существует ли файл
         if (!file_exists($path)) {
            return [
               "object" => (object) [
                  "success" => false,
                  "debug" => true,
                  "message" => "Файл '" . basename($path) . "' не существует.",
                  "result" => null,
               ],
               "status" => 500,
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
               return [
                  "object" => (object) [
                     "success" => false,
                     "debug" => true,
                     "message" => "Раздел '" . $sheet->getTitle() . "' в файле '" . basename($path) . "' пустой.",
                     "result" => null,
                  ],
                  "status" => 500,
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

         return [
            "object" => (object) [
               "success" => true,
               "debug" => false,
               "message" => "Данные с файла считаны.",
               "result" => $sheetsAlldata,
            ],
            "status" => 200,
         ];
      } catch (Throwable $e) {
         return [
            "object" => (object) [
               "success" => false,
               "debug" => true,
               "message" => "Не удалось считать данные.",
               "result" => null,
            ],
            "status" => 500,
         ];
      };
   }

   /* Проверка на пустой лист */
   function isExcelEmpty($sheet)
   {
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
   function getMainCategory($categories, $categorylevel)
   {
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

   /* Получение файла и удаление */
   public function downloadPricesArchive($type)
   {
      if (empty($type)) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Тип файла не указан.",
            "result" => null,
         ], 422);
      };

      switch ($type) {
         case 'xlsx':
            return $this->downloadPricesArchiveXLSX($type);
            break;
         case 'xml':
            return  $this->downloadPricesArchiveXML($type);
            break;
      };
   }

   /* Выгрузка: XLSX */
   public function downloadPricesArchiveXLSX($type)
   {
      // Получаем список файлов без полных путей
      $files = Storage::disk('public')->files('reports/xlsx');

      if (empty($files)) {
         return response()->json(['message' => 'No files to download'], 404);
      }

      $zip = new ZipArchive;
      $zipFileName = 'prices-xlsx-' . time() . '.zip';
      $zipPath = storage_path('app/public/' . $zipFileName);

      if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
         foreach ($files as $file) {
            // Получаем только имя файла без директории
            $fileName = basename($file);

            // Добавляем файл в архив с указанием правильного пути
            $zip->addFile(storage_path('app/public/' . $file), $fileName);
         }

         $zip->close();
      } else {
         return response()->json(['message' => 'Failed to create zip archive'], 500);
      }

      return response()->download($zipPath)->deleteFileAfterSend(true);
   }

   /* Выгрузка: XML */
   public function downloadPricesArchiveXML()
   {
      // Получаем список файлов без полных путей
      $files = Storage::disk('public')->files('reports/xml');

      if (empty($files)) {
         return response()->json(['message' => 'No files to download'], 404);
      }

      $zip = new ZipArchive;
      $zipFileName = 'prices-xml-' . time() . '.zip';
      $zipPath = storage_path('app/public/' . $zipFileName);

      if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
         foreach ($files as $file) {
            // Получаем только имя файла без директории
            $fileName = basename($file);

            // Добавляем файл в архив с указанием правильного пути
            $zip->addFile(storage_path('app/public/' . $file), $fileName);
         }

         $zip->close();
      } else {
         return response()->json(['message' => 'Failed to create zip archive'], 500);
      }

      return response()->download($zipPath)->deleteFileAfterSend(true);
   }

   /* Создание файлов с ценами */
   public function makePricesFiles($type, Request $request)
   {
      // Проверка наличия типа
      if (empty($type)) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Тип не указан.",
            "result" => null,
         ], 422);
      };

      switch ($type) {
         case 'xlsx':
            return $this->makePricesFilesXLSX($request);
            break;
         case 'xml':
            return $this->makePricesFilesXML($request);
            break;
      };
   }

   /* Создание файлов с ценами */
   public function makePricesFilesXLSX()
   {
      try {
         // Проверка наличия директории
         if (!Storage::exists('public/reports/xlsx')) {
            Storage::makeDirectory('public/reports/xlsx');
         };

         // Удаление старых файлов
         $files = Storage::disk('public')->allFiles('reports/xlsx');
         Storage::disk('public')->delete($files);

         $fields = [
            1 => 'A',
            2 => 'B',
            3 => 'C',
         ];

         $fieldsTitles = [
            1 => 'Категория',
            2 => 'Название',
            3 => 'Цена',
         ];

         $addressesAll = PriceAddress::all();

         if (count($addressesAll) === 0) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Список адресов пуст.",
               "result" => null,
            ], 500);
         };

         $categoriesAll = PriceCategory::all()->groupBy('addressId');

         if (count($categoriesAll) === 0) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Список категорий пуст.",
               "result" => null,
            ], 500);
         };

         $test = [];

         foreach ($addressesAll as $key => $value) {
            // Создание нового табличного документа
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Установка ширины столбцов
            foreach ($fields as $fieldsKey => $fieldsValue) {
               $sheet->getColumnDimension($fieldsValue)->setAutoSize(true);
            }

            // Заполнение заголовков
            foreach ($fieldsTitles as $fieldsTitlesKey => $fieldsTitlesValue) {
               $sheet->setCellValue($fields[$fieldsTitlesKey] . '1', $fieldsTitlesValue);
            };

            // Получение всех категорий по адресу
            $categories = $categoriesAll[$value->id]->groupBy('id');

            // Получение всех цен
            $prices = PriceValue::all()->whereIn(
               'categoryId',
               $categories->keys()->toArray()
            );

            // Заполнение данных
            $count = 2;
            foreach ($prices as $priceKey => $priceValue) {
               $sheet->setCellValue($fields[1] . ($count), $categories[$priceValue->categoryId][0]->name);
               $sheet->setCellValue($fields[2] . ($count), $priceValue->name);
               $sheet->setCellValue($fields[3] . ($count), $priceValue->price);

               $count++;
            };

            // Сохранение файла в папке storage
            $fileName = $value->name . '.xlsx';
            $files[] = $fileName;

            $writer = new Xlsx($spreadsheet);
            $filePath = storage_path('app/public/reports/xlsx/' . $fileName);
            $writer->save($filePath);
         }

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => 'Файл успешно создан.',
            "result" => '/api/download-prices-archive/xlsx',
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось создать файл.",
            "result" => null,
         ], 500);
      };
   }

   /* Создание файлов с ценами */
   public function makePricesFilesXML(Request $request)
   {
      try {
         // Проверка наличия директории
         if (!Storage::exists('public/reports/xml')) {
            Storage::makeDirectory('public/reports/xml');
         };

         // Удаление старых файлов
         $files = Storage::disk('public')->allFiles('reports/xml');
         Storage::disk('public')->delete($files);

         $addressesAll = PriceAddress::all();

         if (count($addressesAll) === 0) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Список адресов пуст.",
               "result" => null,
            ], 500);
         };

         $categoriesAll = PriceCategory::all()->groupBy('addressId');
         $pricesAll = PriceValue::all();

         if (count($categoriesAll) === 0) {
            return response()->json([
               "success" => false,
               "debug" => true,
               "message" => "Список категорий пуст.",
               "result" => null,
            ], 500);
         };

         foreach ($addressesAll as $key => $value) {
            $categories = $categoriesAll[$value->id];
            
            $prices = $pricesAll->whereIn(
               'categoryId',
               $categories->pluck('id')->toArray()
            );

            // Создание XML
            $xw = new XMLWriter();
            $xw->openMemory();

            // Начало документа
            $xw->startDocument("1.0");

            // yml_catalog
            $xw->startElement("yml_catalog");

            // yml_catalog -> shop
            $xw->startElement("shop");

            // yml_catalog -> shop -> categories
            $xw->startElement("categories");
            // yml_catalog -> shop -> categories -> category
            foreach ($categories as $categoryKey => $categoryValue) {
               $xw->startElement("category");

               $xw->startAttribute("id");
               $xw->text($categoryValue->id);
               $xw->endAttribute();

               // yml_catalog -> shop -> categories -> category
               $xw->endElement();
            };
            // yml_catalog -> shop -> categories
            $xw->endElement();

            // yml_catalog -> shop -> offers
            $xw->startElement("offers");
            // yml_catalog -> shop -> offers -> offer
            foreach ($prices as $priceKey => $priceValue) {
               $xw->startElement("offer");

               $xw->startAttribute("id");
               $xw->text($priceValue->id);
               $xw->endAttribute();

               // yml_catalog -> shop -> offers -> offer -> name
               $xw->startElement("name");
               $xw->text($priceValue->name);
               $xw->endElement();

               // yml_catalog -> shop -> offers -> offer -> price
               $xw->startElement("price");
               $xw->text($priceValue->price);
               $xw->endElement();

               // yml_catalog -> shop -> offers -> offer -> currencyId
               $xw->startElement("currencyId");
               $xw->text("RUR");
               $xw->endElement();

               // yml_catalog -> shop -> offers -> offer -> categoryId
               $xw->startElement("categoryId");
               $xw->text($priceValue->categoryId);
               $xw->endElement();

               // yml_catalog -> shop -> offers -> offer -> picture
               $xw->startElement("picture");
               $xw->text($request->root() . '/storage/img/logo.webp');
               $xw->endElement();

               // yml_catalog -> shop -> offers -> offer -> url
               $xw->startElement("url");
               $xw->text($request->root() . '/prices');
               $xw->endElement();

               // yml_catalog -> shop -> categories -> category
               $xw->endElement();
            };

            // yml_catalog -> shop -> categories
            $xw->endElement();

            // yml_catalog -> shop
            $xw->endElement();

            // yml_catalog
            $xw->endElement();

            // Конец документа
            $xw->endDocument();

            if (!Storage::put('public/reports/xml/' . $value->name . '.xml', $xw->outputMemory())) {
               return response()->json([
                  "success" => false,
                  "debug" => true,
                  "message" => "Не удалось записать файл.",
                  "result" => null,
               ], 500);
            };
         };

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "XML-файлы созданы.",
            "result" => '/api/download-prices-archive/xml',
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => "Не удалось создать файл.",
            "result" => null,
         ], 500);
      };
   }
}
