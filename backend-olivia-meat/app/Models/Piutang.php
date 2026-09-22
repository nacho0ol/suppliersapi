<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    protected $table = 'piutang';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false; 
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan', 
        'tgl_jatuh_tempo', 
        'total_tagihan', 
        'jumlah_terbayar', 
        'status_piutang', 
        'is_deleted'
    ];
}