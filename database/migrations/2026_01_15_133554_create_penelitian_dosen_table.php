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
        Schema::create('penelitian_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penelitian_id');
            $table->unsignedBigInteger('dosen_id');
            $table->timestamps();

            $table->foreign('penelitian_id')->references('id')->on('penelitians')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penelitian_dosen');
    }
};
