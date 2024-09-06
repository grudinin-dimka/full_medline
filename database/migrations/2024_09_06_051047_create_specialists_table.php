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
            $table->text('filename')->nullable();
            $table->timestamps();
        });

        DB::table('specialists')->insert([
            "name" => "Иванов Иван Иванович",
            "specialization" => "Терапевт",
            "startWorkAge" => date("c"),
            "education" => "Высшее",
            "advancedTraining" => "Терапевт",
            "certificates" => "Сертификат",
            "filename" => "doctor.png"
        ]);

        DB::table('specialists')->insert([
            "name" => "Ёжиков Ёжик Ёжикович",
            "specialization" => "Эндокринолог",
            "startWorkAge" => date("c"),
            "education" => "Высшее",
            "advancedTraining" => "Терапевт",
            "certificates" => "Сертификат",
            "filename" => "doctor.png"
        ]);

        DB::table('specialists')->insert([
            "name" => "Васьков Василий Васильевич",
            "specialization" => "Эндокринолог",
            "startWorkAge" => date("c"),
            "education" => "Высшее",
            "advancedTraining" => "Терапевт",
            "certificates" => "Сертификат",
            "filename" => "doctor.png"
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
