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
            $table->unsignedInteger('index')->nullable()->default(000000);
            $table->float('geoWidth')->nullable();
            $table->float('geoLongitude')->nullable();
            $table->string('other')->nullable();
            $table->timestamps(); 
        });

        DB::table('clinics')->insert(
            [
                "name" => "г. Шадринск, ул. Комсомольская, 16",
                "city" => "Шадринск",
                "street" => "Комсомольская",
                "home" => "16",
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
