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
        Schema::table('dosens', function (Blueprint $table) {
            $table->text('google_drive_access_token')->nullable()->after('google_drive_folder_id');
            $table->text('google_drive_refresh_token')->nullable()->after('google_drive_access_token');
            $table->timestamp('google_drive_token_expires_at')->nullable()->after('google_drive_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn([
                'google_drive_access_token',
                'google_drive_refresh_token',
                'google_drive_token_expires_at',
            ]);
        });
    }
};
