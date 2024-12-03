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
        Schema::create('price_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->boolean('status');
            $table->timestamps();
        });

        DB::table('price_files')->insert(
            [
                ['filename' => 'price1.ods', 'status' => false, 'created_at' => now(), 'updated_at' => now()],
                ['filename' => 'price2.ods', 'status' => false, 'created_at' => now(), 'updated_at' => now()],
                ['filename' => 'price3.ods', 'status' => true, 'created_at' => now(), 'updated_at' => now()],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_files');
    }
};
