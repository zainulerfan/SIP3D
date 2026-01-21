<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom TPK ke tabel dosens untuk integrasi dengan sistem penilaian kinerja.
     */
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->integer('skor_sinta')->default(0)->after('status');
            $table->integer('skor_sinta_3yr')->default(0)->after('skor_sinta');
            $table->integer('jumlah_buku')->default(0)->after('skor_sinta_3yr');
            $table->integer('jumlah_hibah')->default(0)->after('jumlah_buku');
            $table->integer('publikasi_scholar')->default(0)->after('jumlah_hibah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {
            $table->dropColumn([
                'skor_sinta',
                'skor_sinta_3yr',
                'jumlah_buku',
                'jumlah_hibah',
                'publikasi_scholar',
            ]);
        });
    }
};
