<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'label',
        'name',
        'type',
        'options',
        'is_required',
        'is_active',
        'position',
    ];

    // Relasi ke Form
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
