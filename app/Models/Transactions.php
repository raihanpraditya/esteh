<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'transactions';
    protected $fillable = ['kode_transaksi', 'transaction_date', 'transactions_time', 'total_amount', 'branch'];
    public $timestamps = false;
    public function transactions()
    {
        return $this->belongsTo(Transaction::class, 'kode_transaksi', 'kode_transaksi');
    }
}
