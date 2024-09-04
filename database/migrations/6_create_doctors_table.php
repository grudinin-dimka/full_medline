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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('specialization')->nullable();
            $table->date('startWorkAge')->nullable()->default(date('c'));
            $table->text('education')->nullable();
            $table->text('advancedTraining')->nullable();
            $table->text('certificates')->nullable();
            $table->text('img')->nullable();
            $table->timestamps();
        });

        DB::table('doctors')->insert([
            "name" => "Иванов Иван Иванович",
            "specialization" => "Терапевт",
            "startWorkAge" => date("c"),
            "education" => "Высшее",
            "advancedTraining" => "Терапевт",
            "certificates" => "Сертификат",
            "img" => "/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png"
        ]);

        DB::table('doctors')->insert([
            "name" => "Ёжиков Ёжик Ёжикович",
            "specialization" => "Эндокринолог",
            "startWorkAge" => date("c"),
            "education" => "Высшее",
            "advancedTraining" => "Терапевт",
            "certificates" => "Сертификат",
            "img" => "/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png"
        ]);

        DB::table('doctors')->insert([
            "name" => "Васьков Василий Васильевич",
            "specialization" => "Эндокринолог",
            "startWorkAge" => date("c"),
            "education" => "Высшее",
            "advancedTraining" => "Терапевт",
            "certificates" => "Сертификат",
            "img" => "/storage/HKK2mLj6iM8WK2z9hkBMCI7YQ1IzBxDI8MYTp8dk.png"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
