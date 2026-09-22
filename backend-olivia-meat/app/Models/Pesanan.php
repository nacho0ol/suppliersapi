<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false; 
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan', 
        'id_pelanggan', 
        'id_user', 
        'tgl_order', 
        'metode_bayar', 
        'status_bayar', 
        'tgl_antar', 
        'status_pemesanan', 
        'is_dibatalkan'
    ];
}