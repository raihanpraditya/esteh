<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;
    protected $table = 'keranjang';
    protected $fillable = ['kode_produk', 'quantity', 'total_produk'];
    public $timestamps = false;
    public function keranjang(){
        return $this->hasMany(Product::class. 'id', 'kode_produk');
    }
}
