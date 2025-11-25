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
        Schema::create('cliente', function (Blueprint $table) {
            $table->string('cedula')->primary();
            $table->integer('id_sorteo');
            $table->string('nombre_y_apellido');
            $table->string('telefono');
            $table->string('correo');
            $table->integer('cantidad_comprados')->default(0)->nullable();
            $table->date('fecha_de_pago');

            $table->foreign('id_sorteo')
                ->references('id_sorteo')
                ->on('sorteo')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
