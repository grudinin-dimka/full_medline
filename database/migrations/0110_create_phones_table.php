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
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
        });

        DB::table('phones')->insert(
            [
                "name" => "+7(000)-000-00-00",
            ]
        );
        DB::table('phones')->insert(
            [
                "name" => "+7(800)-555-35-35",
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phones');
    }
};
