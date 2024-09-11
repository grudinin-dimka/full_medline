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
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        DB::table('specializations')->insert([
            "name" => "Главный врач",
        ]);
        DB::table('specializations')->insert([
            "name" => "Онколог",
        ]);
        DB::table('specializations')->insert([
            "name" => "Терапевт",
        ]);
        DB::table('specializations')->insert([
            "name" => "Хирург",
        ]);
        DB::table('specializations')->insert([
            "name" => "Кардиолог",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};
