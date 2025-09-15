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
            $table->integer('nro_ranuras');
            $table->string('dir_imagen');
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
