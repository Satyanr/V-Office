<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparepartRepairTbl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function ledtbls()
    {
        return $this->hasMany(LedTbl::class, 'id_sparepart_repair');
    }
}
