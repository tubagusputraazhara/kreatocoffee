<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahanBaku';
    protected $guarded = [];

    // ❗ Tambahan penting
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('id', 'desc')->first();

            if (!$last) {
                $number = 1;
            } else {
                $number = (int) substr($last->id, 1) + 1;
            }

            $model->id = 'B' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }
}
//