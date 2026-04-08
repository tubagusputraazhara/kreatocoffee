<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja'; // Tanpa 's'

    protected $guarded = [];

    public static function getIdMeja()
    {
        $sql = "SELECT IFNULL(MAX(id_meja), 'MJ-000') as id_meja FROM meja";
        $result = DB::select($sql);

        foreach ($result as $row) {
            $last = $row->id_meja;
        }

        $number = substr($last, -3);
        $number = (int) $number + 1;

        return 'MJ-' . str_pad($number, 3, "0", STR_PAD_LEFT);
    }
}