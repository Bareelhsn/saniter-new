<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JumlahIzin extends Model
{
    use HasFactory;

    protected $table = 'jumlah_izin';
    protected $guarded = ['id'];
}
