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
        // Add Google Drive folder column to penelitians table
        Schema::table('penelitians', function (Blueprint $table) {
            $table->string('google_drive_folder')->nullable()->after('tahun');
        });

        // Add Google Drive folder column to pengabdians table
        Schema::table('pengabdians', function (Blueprint $table) {
            $table->string('google_drive_folder')->nullable()->after('tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penelitians', function (Blueprint $table) {
            $table->dropColumn('google_drive_folder');
        });

        Schema::table('pengabdians', function (Blueprint $table) {
            $table->dropColumn('google_drive_folder');
        });
    }
};
