<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      /* ____________________________________________________*/
      /* Информационные блоки                                */
      /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
      // 1) id - уникальный идентификатор
      // 2) description - описание
      // 3) imageOne - путь к первой картинке
      // 4) imageTwo - путь ко второй картинке
      // 5) imageThree - путь к третьей картинке
      Schema::create('abouts', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('order');
         $table->string('title')->nullable();
         $table->string('description')->nullable();
         $table->string('imageOne')->nullable();
         $table->string('imageTwo')->nullable();
         $table->string('imageThree')->nullable();
         $table->timestamps();
      });
 
      DB::table('abouts')->insert(
         [
            "order" => "1",
            "title" => "Заголовок блока о нас",
            "description" => "Описание какой-то инфо-блока",
            "imageOne" => "1.jpg",
            "imageTwo" => "2.jpg",
            "imageThree" => "3.jpg",
         ]
      );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('abouts');
   }
};
