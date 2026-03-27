<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            
            // leader_id nullable karena bisa absen mandiri via GPS
            $table->foreignId('leader_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained('projects', 'project_id')->onDelete('set null');
            
            $table->dateTime('clock_in_time');
            $table->dateTime('clock_out_time')->nullable();
            
            // Lokasi Masuk & Pulang
            $table->string('latitude'); 
            $table->string('longitude');
            $table->string('latitude_out')->nullable();
            $table->string('longitude_out')->nullable();
            
            // Bukti Foto Selfie
            $table->string('image_url'); // Foto Masuk
            $table->string('image_out_url')->nullable(); // Foto Pulang
            
            // Kalkulasi Menit (Global Shift)
            $table->integer('late_minutes')->default(0); 
            $table->integer('early_leave_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            
            // Tambahkan status 'on_duty'
            $table->enum('status_attendance', ['on_time', 'late', 'early_leave', 'on_duty'])->default('on_duty');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};