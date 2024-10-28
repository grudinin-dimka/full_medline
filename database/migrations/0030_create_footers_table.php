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
        Schema::create('footers', function (Blueprint $table) {
            $table->id();
            $table->string('title', length: 500)->nullable();
            $table->string('titleDesc', length: 1000)->nullable();
            $table->string('license', length: 500)->nullable();
            $table->string('licenseDesc', length: 1000)->nullable();
            $table->string('footer', length: 1000)->nullable();
            $table->timestamps();
        });

        DB::table('footers')->insert([
            "title" => "",
            "titleDesc" => "",
            "license" => "",
            "licenseDesc" => "",
            "footer" => "",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('footers');
    }
};
