<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('communes', function (Blueprint $table) {
            $table->id('id_com');
            $table->unsignedBigInteger('id_reg')->comment('');
            $table->string('description', 90)->comment('');
            $table->enum('status', ['A', 'I', 'trash'])->default('A');
            $table->timestamps();

            $table->foreign('id_reg')->references('id_reg')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communes');
    }
};
