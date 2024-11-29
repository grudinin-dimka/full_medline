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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false);
            $table->string('filename');            
            $table->timestamps();
        });

        DB::table('prices')->insert(
            [
                ['status' => false, 'filename' => 'price1.ods'],
                ['status' => false, 'filename' => 'price2.ods'],
                ['status' => true, 'filename' => 'price3.ods'],
            ]
        );
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
