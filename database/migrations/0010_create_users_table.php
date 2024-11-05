<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->foreignId('rightsId')->references('id')->on('rights')->onDelete('cascade');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
   
        DB::table('users')->insert([
            'name' => 'sveta',
            'email' => 'sveta@admin',
            'rightsId' => 1,
            'password' => Hash::make('zBkvUoXKZ3ib-m6n'),
        ]);

        DB::table('users')->insert([
            'name' => 'danil',
            'email' => 'danil@admin',
            'rightsId' => 1,
            'password' => Hash::make('quwG7G_UR0iVktB7'),
        ]);

        DB::table('users')->insert([
            'name' => 'dima',
            'email' => 'dima@admin',
            'rightsId' => 1,
            'password' => Hash::make('DFKEhwCA4H-s7CoZ'),
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
