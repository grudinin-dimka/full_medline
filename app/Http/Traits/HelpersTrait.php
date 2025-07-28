<?

namespace App\Http\Traits;

use Transliterator;

trait HelpersTrait
{
   /* Создание ссылки */
   public function makeUrl($url)
   {
      $stringTransliterate = $this->formatStringTransliterate($url, 'lower');
      $stringReplace = str_replace(" ", "-", $stringTransliterate);

      return rtrim($stringReplace, '-');
   }

   /* Форматирование строки */
   public function formatStringTransliterate($value, $type = 'lower')
   {
      $stringTransliterate = Transliterator::create('Any-Latin; Latin-ASCII')->transliterate($value);
      $stringByCase = '';

      switch ($type) {
         case 'upper':
            $stringByCase = strtoupper($stringTransliterate);
            break;
         case 'lower':
            $stringByCase = strtolower($stringTransliterate);
            break;
         default:
            # code...
            break;
      }

      return $stringByCase;
   }
}
