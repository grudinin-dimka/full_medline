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
        /* 2.1. Специализации специалистов                     */
        /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
        // 1) id - уникальный идентификатор
        // 2) id_specialist - ссылка на id специалиста
        // 3) id_specialization - ссылка на id специализации
        // 4) category - категория специализации
        Schema::create('specialist_specializations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_specialist')->references('id')->on('specialists')->onDelete('cascade');
            $table->unsignedBigInteger('id_specialization')->references('id')->on('specializations')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('specialist_specializations')->insert(
            [
                "id_specialist" => 1,
                "id_specialization" => 1,
            ]
        );

        DB::table('specialist_specializations')->insert(
            [
                "id_specialist" => 1,
                "id_specialization" => 2,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialist_specializations');
    }
};
