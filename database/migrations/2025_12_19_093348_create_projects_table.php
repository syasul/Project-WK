<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id('project_id');
            $table->string('project_code')->unique();
            $table->string('name');
            $table->string('client_name')->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            
            // --- BAGIAN YANG KURANG (TAMBAHKAN INI) ---
            // Kita harus membuat kolomnya dulu agar bisa diberi Foreign Key
            $table->unsignedBigInteger('location_id')->nullable(); 

            // --- BARU DEFINISI FOREIGN KEY ---
            $table->foreign('location_id')
                ->references('location_id') 
                ->on('locations')
                ->onDelete('set null');
            
            // Keuangan
            $table->decimal('project_value', 15, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            
            // Operasional
            $table->enum('status', ['planned', 'ongoing', 'completed', 'cancelled'])->default('ongoing');
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};