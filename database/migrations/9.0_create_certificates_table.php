<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
Use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      /* ____________________________________________________*/
      /* 6. Сертификаты                                      */
      /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
      // 1) id - уникальный идентификатор
      // 2) organization - организация
      // 3) date - дата получения
      // 4) name - название
      Schema::create('certificates', function (Blueprint $table) {
         $table->id();
         $table->string('organization', 1000);
         $table->date('endEducation')->default(date('c'));
         $table->string('name', 1000);
         $table->timestamps();
      });
      
      DB::table('certificates')->insert(
         [
             "organization" => "Какой-то центр сертификации",
             "endEducation" => date("Y-m-d"),
             "name" => "Ультразвуковая дигностика",
         ]
      );
      DB::table('certificates')->insert(
         [
             "organization" => "Какой-то центр сертификации",
             "endEducation" => date("Y-m-d"),
             "name" => "Узи брюшной полости",
         ]
      );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('certificates');
   }
};
