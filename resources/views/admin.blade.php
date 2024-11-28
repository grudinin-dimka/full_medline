<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/png" size="100x100">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <!-- Yandex.Maps -->
        <script src="https://api-maps.yandex.ru/v3/?apikey=599ae210-3f94-4561-9fbb-3668aa323749&lang=ru_RU"></script>
    </head>
    <body>
        <div id="app"></div>
        @vite('resources/vue/main.js')
    </body>
</html>