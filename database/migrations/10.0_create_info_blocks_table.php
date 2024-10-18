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
      /* 7. Информационные блоки                             */
      /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
      // 1) id - уникальный идентификатор
      // 2) description - описание
      // 3) imageOne - путь к первой картинке
      // 4) imageTwo - путь ко второй картинке
      // 5) imageThree - путь к третьей картинке
      Schema::create('info_blocks', function (Blueprint $table) {
         $table->id();
         $table->string('description')->nullable();
         $table->string('imageOne')->nullable();
         $table->string('imageTwo')->nullable();
         $table->string('imageThree')->nullable();
         $table->timestamps();
      });
   
      DB::table('info_blocks')->insert(
         [
            "description" => "Описание какой-то инфо-блока",
            "imageOne" => "",
            "imageTwo" => "Ультразвуковая дигностика",
            "imageThree" => "Ультразвуковая дигностика",
         ]
      );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('info_blocks');
   }
};
