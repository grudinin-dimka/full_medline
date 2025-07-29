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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index()->default('none');
            $table->string('ip')->index()->nullable()->default('none');
            $table->string('user_agent')->nullable()->default('none');
            $table->string('meta')->nullable()->default('none');
            $table->timestamps();
        });

        Schema::table('trackings', function (Blueprint $table) {
            $table->index('created_at');
            $table->index(['created_at', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
