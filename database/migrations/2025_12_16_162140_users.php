<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');

            // Relasi Shift (Set Null jika shift dihapus agar user tidak ikut terhapus)
            $table->foreignId('shift_id')
                ->nullable()
                ->constrained('shifts', 'shift_id')
                ->nullOnDelete();

            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('position')->nullable();

            $table->enum('role', ['admin', 'leader', 'employee'])->default('employee');
            $table->enum('status', ['pending', 'active', 'rejected', 'banned'])->default('pending');

            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }



    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};