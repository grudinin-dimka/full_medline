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
        /* ____________________________________________________*/
        /* 2. Специализации                                    */
        /* ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾*/
        // 1) id - Идентификатор
        // 2) name - название специализации
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300);
            $table->timestamps();
        });

        // $list = [
        //     "Хирург",
        //     "Терапевт",
        //     "Акушер",
        //     "Аллерголог",
        //     "Кардиолог",
        //     "Нейрохирург",
        //     "Невролог",
        //     "Оториноларинголог",
        //     "Офтальмолог",
        // ];

        // foreach ($list as $item) {
        //     DB::table('specializations')->insert(
        //         ["name" => $item],
        //     );
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specializations');
    }
};
