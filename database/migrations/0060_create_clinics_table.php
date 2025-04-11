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
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('city', 500);
            $table->string('street', 500);
            $table->string('home', 30);
            $table->unsignedInteger('index')->default(000000);
            $table->float('geoWidth');
            $table->float('geoLongitude');
            $table->string('other')->nullable()->default('');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
