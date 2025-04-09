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
            $table->string('type'); // 'page_visit', 'form_click' и т.д.
            $table->string('ip')->nullable();
            $table->string('url')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('meta')->nullable(); // Доп. данные (например, ID формы)

            $table->foreignId('user_id')->nullable()->constrained( 
                table: 'users', 
                column: 'id',
             )->nullOnDelete();

            $table->timestamps();
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
