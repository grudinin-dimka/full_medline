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
      Schema::create('travels', function (Blueprint $table) {
         $table->id();
         $table->string('title')->unique();
         $table->string('duration');
         $table->text('description');
         $table->string('image');
         $table->integer('order');
         $table->boolean('hide')->default(false);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('travels');
   }
};
