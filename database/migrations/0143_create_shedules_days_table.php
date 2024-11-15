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
        Schema::create('shedules_days', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('sheduleId')->references('id')->on('shedules')->onDelete('cascade');
            $table->foreignId('clinicId')->references('id')->on('clinics')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shedules_days');
    }
};
