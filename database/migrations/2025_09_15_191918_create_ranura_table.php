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
            $table->integer('id_ruleta');
            $table->integer('id_ranura')->primary()->autoIncrement();
            $table->integer('orden')->default(0);
            $table->string('color');
            $table->string('dir_imagen')->nullable();
            $table->string('type');
            $table->string('texto')->nullable();
            $table->integer('rate');
            $table->boolean('blocked')->default(false);
            
            $table->timestamps();

            $table->foreign('id_ruleta')
                    ->references('id_ruleta')
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
