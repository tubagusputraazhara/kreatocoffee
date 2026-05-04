<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    use HasFactory;

    protected $table = 'jurnal_umum';

    protected $primaryKey = 'id_jurnal';

    protected $guarded = [];

    public function detailJurnal()
    {
        return $this->hasMany(DetailJurnalUmum::class, 'id_jurnal');
    }
}