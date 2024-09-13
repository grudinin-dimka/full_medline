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
      // 4) organization - описание
      // 5) name - описание
      Schema::create('works', function (Blueprint $table) {
         $table->id();
         $table->date('startWork')->default(date('c'));
         $table->date('endWork')->default(date('c'));
         $table->string('organization', 1000);
         $table->string('name', 1000);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('works');
   }
};
