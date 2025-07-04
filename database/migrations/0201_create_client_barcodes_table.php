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
      Schema::create('client_barcodes', function (Blueprint $table) {
         $table->id();
         $table->string('barcode_value');
         $table->string('barcode_type');
         $table->string('type');
         $table->string('value');

         $table->foreignId('client_id')->constrained(
            table: 'clients',
            column: 'id',
         )->onDelete('cascade');

         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('client_barcodes');
   }
};
