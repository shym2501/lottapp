<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'email',
        'phone',
        'code',
        'verified',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
