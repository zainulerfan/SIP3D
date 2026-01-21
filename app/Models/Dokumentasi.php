<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $fillable = [
        'penelitian_id',
        'pengabdian_id',
        'mahasiswa_id',
        'jenis',
        'file_path',
        'drive_link',
    ];

    public function penelitian()
    {
        return $this->belongsTo(Penelitian::class);
    }

    public function pengabdian()
    {
        return $this->belongsTo(Pengabdian::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
