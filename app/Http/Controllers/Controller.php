<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Transliterator;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /* Создание ссылки */
    public function makeUrl($url) {
        $stringTransliterate = $this->formatStringTransliterate($url, 'lower');
        $stringReplace = str_replace(" ", "-", $stringTransliterate);

        return rtrim($stringReplace, '-');
    }

    /* Форматирование строки */
    public function formatStringTransliterate($value, $type = 'lower') {
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
