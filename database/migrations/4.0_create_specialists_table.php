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
        /* 1. Основная таблица                                 */
        /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
        // 1) id - Идентификатор
        // 2) family - фамилия
        // 3) name - имя
        // 4) surname - отчество
        // 5) startWorkAge - дата начала каръеры
        // 6) startWorkCity - город начала каръеры
        // 7) adultDoctor - взрослый доктор (да / нет)
        // 8) childrenDoctor - детский доктор (да / нет)
        // 9) hide - статус скрытия на сайте
        // 10) filename - название прикреплённого файла
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->string('family', 300)->nullable();
            $table->string('name', 300)->nullable();
            $table->string('surname', 300)->nullable();
            $table->date('startWorkAge')->nullable();
            $table->string('startWorkCity', 300)->nullable();
            $table->boolean('adultDoctor')->nullable();
            $table->boolean('childrenDoctor')->nullable();
            $table->boolean('hide')->default(false);
            $table->string('filename')->nullable();
            $table->timestamps();
        });

        DB::table('specialists')->insert([
            "family" => "Власов",
            "name" => "Павел",
            "surname" => "Александрович",
            "startWorkAge" => date('c'),
            "startWorkCity" => "Москва",
            "adultDoctor" => true,
            "childrenDoctor" => false,
            "filename" => "vlasov.png"
        ]);
        DB::table('specialists')->insert([
            "family" => "Дымшакова",
            "name" => "Наталья",
            "surname" => "Юрьевна",
            "startWorkAge" => date('c'),
            "startWorkCity" => "Москва",
            "adultDoctor" => true,
            "childrenDoctor" => false,
            "filename" => "dimshakova.png"
        ]);
        DB::table('specialists')->insert([
            "family" => "Кузовникова",
            "name" => "Тамара",
            "surname" => "Георгиевна",
            "startWorkAge" => date('c'),
            "startWorkCity" => "Москва",
            "adultDoctor" => true,
            "childrenDoctor" => false,
            "filename" => "kuzovnikova.png"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialists');
    }
};
