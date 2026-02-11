<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartFlowTbl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function tiket()
    {
        return $this->belongsTo(TiketTbl::class, 'id_tiket');
    }

    public function led()
    {
        return $this->belongsTo(LedTbl::class, 'id_led');
    }

}
