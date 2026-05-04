<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'detail_jurnal_umum';

    protected $primaryKey = 'id_detail_jurnal';

    protected $guarded = [];

    public function jurnal()
    {
        return $this->belongsTo(JurnalUmum::class, 'id_jurnal');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa');
    }
}