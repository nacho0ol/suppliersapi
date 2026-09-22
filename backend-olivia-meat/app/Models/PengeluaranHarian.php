<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranHarian extends Model
{
    protected $table = 'pengeluaran_harian';
    protected $primaryKey = 'id_pengeluaran';
    public $timestamps = false;

    protected $fillable = [
        'tgl_pengeluaran', 
        'kategori_pengeluaran', 
        'id_user', 
        'nominal', 
        'keterangan', 
        'is_deleted'
    ];
}