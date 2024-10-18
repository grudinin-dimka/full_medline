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
            "title" => "© 2ОО6 «МедЛайн»",
            "titleDesc" => "Все материалы данного сайта являются объектами авторского права (в том числе дизайн). Запрещается копирование, распространение (в том числе путем копирования на другие сайты и ресурсы в Интернете) или любое иное использование информации и объектов без предварительного письменного согласия правообладателя. Указание ссылки на источник информации является обязательным.",
            "license" => "Лицензия № ?",
            "licenseDesc" => "Материалы, размещенные на данной странице, носят информационный характер и предназначены для образовательных целей. Посетители сайта не должны использовать их в качестве медицинских рекомендаций. Определение диагноза и выбор методики лечения остается исключительной прерогативой вашего лечащего врача! ООО «Медицинская Линия» не несёт ответственности за возможные негативные последствия, возникшие в результате использования информации, размещенной на сайте https://medlinegroup.ru",
            "footer" => "Администрация клиники принимает все меры по своевременному обновлению размещенного на сайте прайс-листа, однако во избежание возможных недоразумений, советуем уточнять стоимость услуг в регистратуре или в контакт-центре по телефону +7 (35253) 9-000-9 . Размещенный прайс не является офертой. Медицинские услуги оказываются на основании договора.",
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
