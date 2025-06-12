<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('medical_notes', function (Blueprint $table) {
            $table->foreignId('appointment_id')->primary(); // Llave Primaria y Foránea
            $table->text('diagnostico');
            $table->text('tratamiento_sugerido');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('medical_notes');
    }
};
