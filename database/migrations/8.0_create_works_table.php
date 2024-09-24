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
      /* 5. Место работы                                     */
      /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
      // 1) id - уникальный идентификатор
      // 2) startWork - дата начала работы
      // 3) endWork - дата окончания работы
      // 4) organization - организация
      // 5) name - название работы
      Schema::create('works', function (Blueprint $table) {
         $table->id();
         $table->date('startWork')->default(date('c'));
         $table->date('endWork')->default(date('c'));
         $table->string('organization', 1000);
         $table->string('name', 1000);
         $table->timestamps();
      });

      DB::table('works')->insert(
         [
            "startWork" => '2010.03.19',
            "endWork" => '2020.03.19',
            "organization" => "ООО 'Медицинский центр'",
            "name" => "Врач-педиатр"
         ]
      );
      DB::table('works')->insert(
         [
            "startWork" => '2021.01.20',
            "endWork" => '2022.06.13',
            "organization" => "ООО 'Медики рулят'",
            "name" => "Директор"
         ]
     );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('works');
   }
};
