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
            $table->unsignedBigInteger('order');
            $table->foreignId('clinicId')->nullable()->references('id')->on('clinics')->onDelete('cascade')->default(null);
            $table->timestamps();
        });

        DB::table('contacts')->insert(
            [
                "name" => "Колл-центр",
                "order" => 1,
                "clinicId" => 2,
            ]
        );

        DB::table('contacts')->insert(
            [
                "name" => "На Комсомольской",
                "order" => 2,
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
