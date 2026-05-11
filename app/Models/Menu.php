<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_menu',
        'nama_menu',
        'harga',
        'kategori',
        'gambar',
        'deskripsi',
    ];
}