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
      Schema::create('certificates', function (Blueprint $table) {
         $table->id();
         $table->string('organization', 1000);
         $table->date('endEducation')->default(date('Y-m-d'));
         $table->string('name', 1000);
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('certificates');
   }
};
