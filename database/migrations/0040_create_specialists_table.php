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
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->string('link', 1000);
            $table->string('family', 300);
            $table->string('name', 300);
            $table->string('surname', 300)->nullable();
            $table->string('category', 100);
            $table->string('degree', 300)->nullable();
            $table->string('rank', 300)->nullable();
            $table->date('startWorkAge')->nullable();
            $table->string('startWorkCity', 300)->nullable();
            $table->boolean('adultDoctor');
            $table->boolean('childrenDoctor');
            $table->integer('childrenDoctorAge')->default(0);
            $table->boolean('hide')->default(false);
            $table->string('filename');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialists');
    }
};
