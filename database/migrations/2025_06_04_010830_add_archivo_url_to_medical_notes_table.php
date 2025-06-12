<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('medical_notes', function (Blueprint $table) {
            $table->string('archivo_url')->nullable()->after('observaciones'); // columna para la ruta del archivo
        });
    }


    public function down(): void
    {
        Schema::table('medical_notes', function (Blueprint $table) {
            $table->dropColumn('archivo_url');
        });
    }
};