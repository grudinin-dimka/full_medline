<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Prices\PriceAddress;

use App\Models\Tracking;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use App\Http\Traits\HelpersTrait;

use Throwable;
use Transliterator;

class TrackLoad
{
   use HelpersTrait;

   /**
    * Handle an incoming request.
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next, string $page = "", string $subpage = ""): Response
   {
      switch ($page) {
         case 'attendance':
            $result = $this->getAttendance($request);
            break;
         case 'specialists':
            $result = $this->getSpecialists($request);
            break;
         case 'prices':
            $result = $this->getPrices($request);
            break;
         case 'clinics':
            $result = $this->getClinics($request);
            break;
         case 'shedule':
            $result = $this->getShedule($request);
            break;
         case 'news':
            $result = $this->getNews($request);
            break;
         case 'video':
            $result = $this->getVideo($request);
            break;
         case 'contacts':
            $result = $this->getContacts($request);
            break;
         case 'about':
            $result = $this->getAbout($request);
            break;
         default:
            $result = (object) [
               "type" => "not defined",
               "meta" => "not defined",
            ];
            break;
      }

      if (isset($result)) {
         $type = $result->type;
         $meta = $result->meta;
      } else {
         return $next($request);
      }

      try {
         // Поиск браузера
         $browser = match (true) {
            str_contains($request->userAgent(), 'Edge') => 'Edge',
            str_contains($request->userAgent(), 'Opera') => 'Opera',
            str_contains($request->userAgent(), 'Firefox') => 'Firefox',
            str_contains($request->userAgent(), 'Chrome') => 'Chrome',
            str_contains($request->userAgent(), 'Safari') => 'Safari',
            default => 'Unknown',
         };

         if (isset($subpage) && $subpage != "") {
            $meta = $meta . " - " . $subpage;
         };

         $track = Tracking::create([
            "type" => $type ?? "not defined",
            "ip" => $request->ip() ?? "not defined",
            "user_agent" => $browser ?? "not defined",
            "meta" => $meta ?? "not defined",
         ]);

         return $next($request);
      } catch (Throwable $e) {
         Log::error($e);

         return $next($request);
      };
   }

   /* Посещение */
   private function getAttendance(Request $request)
   {
      return (object) [
         "type" => "Посещение",
         "meta" => "Посещение",
      ];
   }

   /* Специалисты */
   private function getSpecialists(Request $request)
   {
      return (object) [
         "type" => "Специалисты",
         "meta" => "Специалисты",
      ];
   }

   /* Цены */
   private function getPrices(Request $request)
   {
      return (object) [
         "type" => "Цены",
         "meta" => "Цены",
      ];
   }

   /* Клиники */
   private function getClinics(Request $request)
   {
      // Если есть параметры: город, улица, дом
      if ($request->has('city') && $request->has('street') && $request->has('house')) {
         $priceAddresses = PriceAddress::all();

         // Флаги для поиска
         $cityFlags = array("г. ", "город", "р.п.", "р. п.", "п.", "п. ");
         $streetFlags = array("ул. ", "улица ");
         $houseFlags = array("д.", "д. ", "дом", "дом ",);

         // Текущий адрес
         $currentAddress = null;

         // Все адреса
         $addresses = [];

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

         return (object) [
            "type" => "Клиники",
            "meta" => $currentAddress ? $currentAddress->name : "Не найдено",
         ];
      };

      // Если есть параметры: адрес, категория
      if ($request->has('address') && $request->has('category')) {
         if (!isset($request->category)) {
            $currentAddress = PriceAddress::where('name', $request->address)->first();

            return (object) [
               "type" => "Клиники",
               "meta" => $currentAddress ? $currentAddress->name : "Не найдено",
            ];
         };
      };

      // Если нет параметров
      return null;
   }

   private function getShedule(Request $request)
   {
      return (object) [
         "type" => "Расписание",
         "meta" => "Расписание врачей",
      ];
   }

   private function getNews(Request $request)
   {
      return (object) [
         "type" => "Новости",
         "meta" => "Список новостей",
      ];
   }

   private function getVideo(Request $request)
   {
      return (object) [
         "type" => "Видео",
         "meta" => "Раздел с видео",
      ];
   }

   private function getContacts(Request $request)
   {
      return (object) [
         "type" => "Контакты",
         "meta" => "Раздел с контактами",
      ];
   }

   private function getAbout(Request $request)
   {
      return (object) [
         "type" => "О нас",
         "meta" => "Раздел с информацией о нас",
      ];
   }
}
