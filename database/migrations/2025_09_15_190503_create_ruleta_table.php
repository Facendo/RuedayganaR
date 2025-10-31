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
        Schema::create('ruleta', function (Blueprint $table) {
            $table->integer('id_ruleta')->primary()->autoIncrement();
            $table->integer('id_sorteo');
            $table->string('nombre');
            $table->string('cantidad_de_opotunidades_por_dar')->default('0');
            $table->integer('nro_ranuras')->default(0);
            $table->string('dir_imagen')->nullable();
            $table->integer('condicional_oportunidades')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_sorteo')
                    ->references('id_sorteo')
                    ->on('sorteo')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruleta');
    }
};
