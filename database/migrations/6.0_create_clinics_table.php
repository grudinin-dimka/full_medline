<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* ____________________________________________________*/
        /* 3. Клиники                                          */
        /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
        // 1) id - Идентификатор
        // 2) name - название клиники
        // 3) city - город
        // 4) street - улица
        // 5) home - дом
        // 6) index - индекс
        // 7) geoWidth - географическая ширина
        // 8) geoLongitude - географическая долгота
        // 9) other - то, что не вошло в другую категорию
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('city', 500);
            $table->string('street', 500);
            $table->unsignedInteger('home');
            $table->unsignedInteger('index');
            $table->float('geoWidth');
            $table->float('geoLongitude');
            $table->string('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
