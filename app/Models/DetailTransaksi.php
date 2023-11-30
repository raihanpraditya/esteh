<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi';
    protected $fillable = ['kode_transaksi', 'kode_produk', 'quantity'];
    public $timestamps = false;
    public function detailTransaksi() {
        return $this->hasMany(DetailTransaksi::class, 'kode_transaksi', 'kode_transaksi');
    }

}
