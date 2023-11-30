<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchs extends Model
{
    use HasFactory;
    protected $table = 'branchs';
    protected $fillable = ['nama_cabang'];
}
