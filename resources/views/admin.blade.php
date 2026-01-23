<!DOCTYPE html>
<html lang="ru">
   <head>
      <!-- Метатеги -->
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

      <!-- PWA -->
      <meta name="theme-color" content="var(--primary-color)">

      <!-- SEO -->
      <meta name="keywords" content="{{ env('VITE_MC_KEYWORDS', '') }}">
      <meta name="description" content="{{ env('VITE_MC_DESCRIPTION', '') }}">
      <meta name="author" content="{{ env('VITE_MC_AUTHOR', '') }}">
   
      <!-- Open Graph -->
      <meta property="og:url" content="{{ env('VITE_MC_OG_URL', '') }}">
      <meta property="og:type" content="{{  env('VITE_MC_OG_TYPE', '') }}" />
      <meta property="og:title" content="{{ env('VITE_MC_OG_TITLE', '') }}">
      <meta property="og:description" content="{{ env('VITE_MC_OG_DESCRIPTION', '') }}">
      <meta property="og:image" content="{{ env('VITE_MC_OG_IMAGE', '') }}">

      <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" size="48x48">
      
      <!-- Yandex.Maps -->
      <script src="https://api-maps.yandex.ru/v3/?apikey=599ae210-3f94-4561-9fbb-3668aa323749&lang=ru_RU"></script>
   </head>
   <body lang="ru">
      <div id="app"></div>
      @vite('resources/vue/main.js')
   </body>
</html>