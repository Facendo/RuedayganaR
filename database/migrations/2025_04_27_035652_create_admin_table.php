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
        Schema::create('admin', function (Blueprint $table) {
            $table->string("user")->primary()->unique();
            $table->integer('id_sorteo');
            $table->string("password")->unique();
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
        Schema::dropIfExists('admind');
    }
};
