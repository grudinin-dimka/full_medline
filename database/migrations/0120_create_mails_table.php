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
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->timestamps();
        });

        DB::table('mails')->insert(
            [
                "name" => "medline-address@mail.ru",
            ]
        );
        DB::table('mails')->insert(
            [
                "name" => "medline-address@mail.ru",
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mails');
    }
};
