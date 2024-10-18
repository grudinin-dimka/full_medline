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
      /* 3.1. Клиники, где принимают специалисты             */
      /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
      // 1) id - Идентификатор
      // 2) id_specialist - ссылка на специалиста
      // 3) id_clinic - ссылка на клинику
      Schema::create('specialist_clinics', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('id_specialist')->references('id')->on('specialists')->onDelete('cascade');
         $table->unsignedBigInteger('id_clinic')->references('id')->on('clinics')->onDelete('cascade');
         $table->boolean('priem');
         $table->timestamps();
      });

      DB::table('specialist_clinics')->insert(
         [
             "id_specialist" => 1,
             "id_clinic" => 1,
             "priem" => false,
         ]
     );
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('specialist_clinics');
   }
};
