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
      <meta name="keywords" content="медлайн, МЕДЛАЙН, МедЛайн, медициниский центр, клиника, клиника медицинского центра, медецинская клиника, медицинский центр в г. Шадринск">
      <meta name="description" content="«МедЛайн» в Шадринске: врачебные приёмы, анализы, УЗИ, процедурный кабинет. Ваше здоровье – наша забота! Доступные цены, отличный сервис!">
      <meta name="author" content="«МедЛайн»">
   
      <!-- Open Graph -->
      <meta property="og:url" content="https://medlinegroup.ru/">
      <meta property="og:type" content="website" />
      <meta property="og:title" content="«МедЛайн» - врачебные приёмы, анализы, УЗИ, процедурный кабинет">
      <meta property="og:description" content="Мы предлагаем врачебные приёмы, анализы, УЗИ, процедурный кабинет в Шадринске. Доступные цены, отличный сервис!">
      <meta property="og:image" content="https://medlinegroup.ru/storage/img/logo.webp">

      <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" size="100x100">
      
      <!-- Yandex.Maps -->
      <script src="https://api-maps.yandex.ru/v3/?apikey=599ae210-3f94-4561-9fbb-3668aa323749&lang=ru_RU"></script>
   </head>
   <body>
      <div id="app"></div>
      @vite('resources/vue/main.js')
   </body>
</html>