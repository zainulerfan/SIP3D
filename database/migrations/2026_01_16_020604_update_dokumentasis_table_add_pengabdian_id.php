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
        Schema::table('dokumentasis', function (Blueprint $table) {
            $table->foreignId('pengabdian_id')->nullable()->after('penelitian_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('penelitian_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumentasis', function (Blueprint $table) {
            $table->dropForeign(['pengabdian_id']);
            $table->dropColumn('pengabdian_id');
            $table->unsignedBigInteger('penelitian_id')->nullable(false)->change();
        });
    }
};
