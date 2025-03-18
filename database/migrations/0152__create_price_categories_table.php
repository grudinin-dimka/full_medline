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
        Schema::create('price_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            
            $table->foreignId('addressId')->index()
                ->references('id')
                ->on('price_addresses')
                ->onDelete('cascade');
            
            $table->foreignId('categoryId')->index()
                ->nullable()
                ->default(null)
                ->references('id')
                ->on('price_categories')
                ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_categories');
    }
};
