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
        Schema::create('premio', function (Blueprint $table) {
            $table->integer('id_premio')->autoIncrement()->unique()->primary();
            $table->integer('id_sorteo');
            $table->string('premio_nombre');
            $table->string('premio_descripcion');
            $table->string('premio_imagen')->nullable();
            $table->timestamps();
        
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
        Schema::dropIfExists('premio');
    }
};
