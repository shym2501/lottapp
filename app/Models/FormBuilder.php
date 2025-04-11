<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
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

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->position = FormBuilder::max('position') + 1;
        });
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position');
    }
}
