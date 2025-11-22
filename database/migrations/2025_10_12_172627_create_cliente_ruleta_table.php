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
        Schema::create('cliente_ruleta', function (Blueprint $table) {
            $table->id();
            $table->integer('id_ruleta')->nullable();
            $table->string('cedula');
            $table->integer('residuo')->default(0);
            $table->integer('oportunidades')->default(0);
            

            $table->foreign('id_ruleta')
                    ->references('id_ruleta')
                    ->on('ruleta')
                    ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_ruleta');
    }
};
