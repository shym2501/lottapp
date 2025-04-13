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
}
