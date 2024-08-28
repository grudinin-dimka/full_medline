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
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order');
            $table->text('name');
            $table->text('link');
            $table->text('path');
            $table->boolean('hide');
            $table->timestamps();
        });

        DB::table('slides')->insert(
            [
              'order' => 1,
              'name' => 'Власов Павел Александрович',
              'link' => 'https://prodoctorov.ru/shadrinsk/vrach/871312-vlasov/',
              'path' => 'vlasov',
              'hide' => false,
            ],
        );    
        DB::table('slides')->insert(
            [
              'order' => 2,
              'name' => 'Дымшакова Наталья Юрьевна',
              'link' => 'https://prodoctorov.ru/shadrinsk/vrach/513584-dymshakova/',
              'path' => 'dimshakova',
              'hide' => false,
            ],
        );    
        DB::table('slides')->insert(
            [
              'order' => 3,
              'name' => 'Кузовникова Тамара Георгиевна',
              'link' => 'https://prodoctorov.ru/shadrinsk/vrach/649542-kuzovnikova/',
              'path' => 'kuzovnikova',
              'hide' => false,
            ],
        );    
        DB::table('slides')->insert(
            [
              'order' => 4,
              'name' => 'Лесникова Анна Леонидовна',
              'link' => 'https://prodoctorov.ru/shadrinsk/vrach/513564-lesnikova/',
              'path' => 'lesnikova',
              'hide' => false,
            ],
        );    
        DB::table('slides')->insert(
            [
              'order' => 5,
              'name' => 'Лукьянов Сергей Анатольевич',
              'link' => 'https://medlinegroup.ru/doctors/doc?smid=2539',
              'path' => 'lukyanov',
              'hide' => false,
            ],
        );    
        DB::table('slides')->insert(
            [
              'order' => 6,
              'name' => 'Оболдин Игорь Михайлович',
              'link' => 'https://prodoctorov.ru/shadrinsk/vrach/513603-oboldin/',
              'path' => 'oboldin',
              'hide' => false,
            ],
        );    
    }

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
