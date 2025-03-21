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
        Schema::create('electro_registros', function (Blueprint $table) {
            $table->id();
            $table->string('dni');
            $table->string('razon_social');
            $table->string('direccion');
            $table->string('codigo_suministro');
            $table->string('serie_medidor');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('electro_registros');
    }
};
