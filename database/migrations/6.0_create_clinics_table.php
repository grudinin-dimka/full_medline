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
            $table->string('home', 30);
            $table->unsignedInteger('index')->default(000000);
            $table->float('geoWidth');
            $table->float('geoLongitude');
            $table->string('other')->nullable()->default('');
            $table->timestamps(); 
        });

        DB::table('clinics')->insert(
            [
                "name" => "На Комсомольской",
                "city" => "Шадринск",
                "street" => "Комсомольская",
                "home" => "16",
                "index" => "641870",
                "geoWidth" => 56.082351,
                "geoLongitude" => 63.631975,
            ]
        );
        DB::table('clinics')->insert(
            [
                "name" => "На Карла Либкнехта",
                "city" => "Шадринск",
                "street" => "Карла Либкнехта",
                "home" => "10",
                "index" => "641870",
                "geoWidth" => 56.079427,
                "geoLongitude" => 63.624617,
            ]
        );
        DB::table('clinics')->insert(
            [
                "name" => "На Октябрьской",
                "city" => "Шадринск",
                "street" => "Октябрьская",
                "home" => "3",

                "index" => "641882",
                "geoWidth" => 56.079829,
                "geoLongitude" => 63.607307,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
