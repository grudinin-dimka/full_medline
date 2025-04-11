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
      Schema::create('specialist_educations', function (Blueprint $table) {
         $table->id();
         $table->foreignId('id_specialist')->references('id')->on('specialists')->onDelete('cascade');
         $table->foreignId('id_education')->references('id')->on('educations')->onDelete('cascade');
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('specialist_educations');
   }
};
