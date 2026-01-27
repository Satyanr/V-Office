<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartLuarTbl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function tikets()
    {
        return $this->hasMany(TiketTbl::class, 'id_sparepart_luar');
    }
}
