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
        Schema::create('ticket', function (Blueprint $table) {
            $table->integer("id_ticket")->autoIncrement()->unique()->primary();
            $table->string('cedula_cliente');
            $table->integer('id_sorteo');
            $table->string('nombre_sorteo');
            $table->string('nombre_cliente');
            $table->string('telefono_cliente');
            $table->json('numeros_seleccionados');
            $table->string('ticket_token')->unique();
            $table->string('ticket_descripcion')->default('pendiente');
            $table->timestamps();

            $table->foreign('cedula_cliente')
                ->references('cedula')
                ->on('cliente')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('ticket');
    }
};
