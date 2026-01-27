<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LedTbl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function sparepart()
    {
        return $this->belongsTo(SparepartTbl::class, 'id_spareparts');
    }

    public function sparepartRusak()
    {
        return $this->belongsTo(SparepartRusakTbl::class, 'id_sparepart_rusak');
    }

    public function sparepartRepair()
    {
        return $this->belongsTo(SparepartRepairTbl::class, 'id_ledtbl');
    }

    public function tikets()
    {
        return $this->hasMany(TiketTbl::class, 'id_ledtbl');
    }
}
