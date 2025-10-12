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
        Schema::create('ranura', function (Blueprint $table) {
            $table->integer('id_ruleta')->autoIncrement();
            $table->integer('id_ranura')->primary()->autoIncrement();
            $table->string('color');
            $table->string('texto');
            $table->integer('Rate');
            $table->boolean('Blocked')->default(false);
            $table->timestamps();
            $table->foreign('id_ruleta')
                    ->references('id')
                    ->on('ruleta')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranura');
    }
};
