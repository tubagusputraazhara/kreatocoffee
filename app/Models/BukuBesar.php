<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuBesar extends Model
{
    // Tidak ada tabel, hanya dipakai sebagai model dummy
    protected $table = 'detail_jurnal_umum';

    protected $primaryKey = 'id_detail_jurnal';

    protected $guarded = [];
}