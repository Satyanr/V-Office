<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiketTbl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function ledTbl()
    {
        return $this->belongsTo(LedTbl::class, 'id_ledtbl');
    }

    public function sparepartLuar()
    {
        return $this->belongsTo(SparepartLuarTbl::class, 'id_sparepart_luar');
    }

    public function sparepartFlows()
    {
        return $this->hasMany(SparepartFlowTbl::class, 'id_tiket');
    }
}
