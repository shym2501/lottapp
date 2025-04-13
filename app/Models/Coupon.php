<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'form_id', 'is_active', 'use_prefix', 'prefix', 'number_type', 'estimasi_peserta'
    ];

    // Relasi dengan tabel Form
    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function getDigitLength(): int
    {
        $e = (int) $this->estimasi_peserta;

        if ($e <= 9)
            return 1;
        if ($e <= 99)
            return 2;
        if ($e <= 999)
            return 3;
        if ($e <= 9999)
            return 4;

        return strlen((string) $e);
    }
}
