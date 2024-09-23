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
      /* 4. Образование                                       /
      /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
      // 1) id - уникальный идентификатор
      // 2) organization - организация
      // 3) date - дата конца обучения
      // 4) name - название
      // 5) id_specialization - ссылка на специализацию
      Schema::create('educations', function (Blueprint $table) {
         $table->id();
         $table->string('name', 1000);
         $table->string('organization', 1000);
         $table->date('date');
         $table->unsignedBigInteger('id_specialization')->references('id')->on('specializations')->onDelete('cascade');
         $table->timestamps();
      });

      DB::table('educations')->insert(
         [
            "name" => "Власов Павел Александрович - 'Терапевт'",
            "organization" => "Российский национальный исследовательский медицинский университет имени Н. И. Пирогова",
            "date" => date("Y-m-d"),
            "id_specialization" => 2
         ]
      );
      DB::table('educations')->insert(
         [
            "name" => "Иванов Иван Иванович - 'Хирург'",
            "organization" => "Первый Санкт-Петербургский государственный медицинский университет имени И. П. Павлова",
            "date" => date("Y-m-d"),
            "id_specialization" => 1
         ]
      );
      DB::table('educations')->insert(
         [
            "name" => "Иванов Иван Иванович - 'Акушер'",
            "organization" => "Сибирский государственный медицинский университет",
            "date" => date("Y-m-d"),
            "id_specialization" => 3
         ]
      );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('educations');
   }
};
