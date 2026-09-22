<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $table = 'pesanan_detail';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan', 
        'id_produk', 
        'qty', 
        'harga_jual_saat_ini', 
        'is_deleted'
    ];
}