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
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('mahasiswas', 'dosen_id')) {
                $table->foreignId('dosen_id')
                      ->after('user_id')
                      ->nullable()
                      ->constrained('dosens')
                      ->cascadeOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'dosen_id')) {
                $table->dropForeign(['dosen_id']);
                $table->dropColumn('dosen_id');
            }
        });
    }
};
