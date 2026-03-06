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
            
            // Relasi
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('leader_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained('projects', 'project_id')->onDelete('set null');
            
            // Waktu Scan
            $table->dateTime('clock_in_time');
            $table->dateTime('clock_out_time')->nullable();
            
            // Kalkulasi Otomatis
            $table->integer('late_minutes')->default(0); 
            $table->integer('early_leave_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            
            // Status Kehadiran
            $table->enum('status_attendance', ['on_time', 'late', 'early_leave', 'holiday_entry'])->default('on_time');
            
            // Lokasi Realtime User
            $table->string('latitude');
            $table->string('longitude');
            
            $table->softDeletes(); // Admin bisa koreksi jika salah scan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};