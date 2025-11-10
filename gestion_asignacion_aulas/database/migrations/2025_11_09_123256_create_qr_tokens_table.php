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
        Schema::create('qr_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendance_records')->onDelete('cascade'); // fk -> attendance_records.id
            $table->string('token')->unique(); // UUID o hash único
            $table->dateTime('expires_at');    // expiración del token
            $table->boolean('used')->default(false); // si ya fue usado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_tokens');
    }
};
