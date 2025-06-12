<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // Si se borra el dueño, se borran sus mascotas
            $table->string('nombre_mascota');
            $table->string('especie'); 
            $table->string('raza')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('foto_url')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
