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
         $table->string('speсialization', 1000);
         $table->timestamps();
      });

      DB::table('educations')->insert(
         [
            "name" => "Высшее образование",
            "organization" => "Российский национальный исследовательский медицинский университет имени Н. И. Пирогова",
            "date" => date("Y-m-d"),
            "speсialization" => "Медецинское дело",
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
