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
        Schema::create('specialists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('specialization')->nullable();
            $table->date('startWorkAge')->nullable()->default(date('c'));
            $table->text('education')->nullable();
            $table->text('advancedTraining')->nullable();
            $table->text('certificates')->nullable();
            $table->boolean('hide')->default(false);
            $table->text('filename')->nullable();
            $table->timestamps();
        });

        DB::table('specialists')->insert([
            "name" => "Власов Павел Александрович",
            "specialization" => "Главный врач, кардиолог.",
            "startWorkAge" => date("c"),
            "education" => "Уральский государственный медицинский университет, 2006 г.",
            "advancedTraining" => "На уточнении.",
            "certificates" => "«кардиология», «терапия», «организация здравоохранения и общественное здоровье».",
            "hide" => false,
            "filename" => "vlasov.png"
        ]);

        DB::table('specialists')->insert([
            "name" => "Кузовникова Тамара Георгиевна",
            "specialization" => "Терапевт.",
            "startWorkAge" => date("c"),
            "education" => "Амурская государственная медицинская академия, 1985 г.",
            "advancedTraining" => "На уточнении.",
            "certificates" => "«Терапия»",
            "hide" => false,
            "filename" => "kuzovnikova.png"
        ]);

        DB::table('specialists')->insert([
            "name" => "Лукьянов Сергей Анатольевич",
            "specialization" => "Хирург-эндокринолог, онколог, детский онколог.",
            "startWorkAge" => date("c"),
            "education" => "Южно-Уральский государственный медицинский университет, 2004 г.",
            "advancedTraining" => "На уточнении.",
            "certificates" => "«хирургия», «лапароскопическая хирургия», «онкология», «ультразвуковая диагностика».",
            "hide" => false,
            "filename" => "lukyanov.png"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialists');
    }
};
