<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'is_active',
        'use_prefix',
        'prefix',
        'number_type',
        'estimate',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
