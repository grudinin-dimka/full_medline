<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->foreignId('clinicId')->references('id')->on('clinics')->onDelete('cascade')->nullable();
            $table->timestamps();
        });

        DB::table('contacts')->insert(
            [
                "name" => "На Комсомольской",
                "clinicId" => 1,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
