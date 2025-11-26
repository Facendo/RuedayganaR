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
        Schema::create('historico_ruleta', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ruleta');
            $table->string('nombre_ruleta', 100);
            $table->string('cedula_jugador', 20);
            $table->string('nombre_jugador', 100);
            $table->string('telefono');
            $table->string('descripcion');
            $table->timestamps();

            $table->foreign('id_ruleta')->references('id_ruleta')->on('ruleta')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_ruleta');
    }
};
