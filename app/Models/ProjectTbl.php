<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTbl extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function konsumen()
    {
        return $this->belongsTo(KonsumenTbl::class, 'id_konsumens');
    }   
    public function layanan()
    {
        return $this->belongsTo(LayananTbl::class, 'id_layanans');
    }
    public function pembayaran()
    {
        return $this->hasMany(PembayaranTbl::class, 'id_project');
    }
}
