<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    use HasFactory;

    protected $table = 'menu'; // Nama tabel eksplisit
    protected $primaryKey = 'id_menu'; // untuk id_menu
    public $incrementing = false;     // isinya M001 (bukan angka 1,2,3)
    protected $keyType = 'string';    // isinya berupa huruf/string

    protected $guarded = [];
}