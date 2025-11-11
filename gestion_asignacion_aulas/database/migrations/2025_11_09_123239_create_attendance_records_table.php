<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade'); // fk -> assignments.id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // fk -> users.id
            $table->dateTime('scan_time');            // hora de marcado
            $table->dateTime('finish_time')->nullable(); // hora de salida opcional
            $table->enum('status', ['on_time', 'late', 'absent']);
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
