<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id('location_id');
            $table->string('name');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('radius')->default(50);
            $table->text('address')->nullable();
            
            $table->foreignId('leader_id')
                ->nullable()
                ->constrained('users', 'user_id') // Arahkan ke kolom user_id di tabel users
                ->nullOnDelete();

            $table->timestamps();
            
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};