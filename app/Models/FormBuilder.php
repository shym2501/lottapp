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

    public static function boot()
    {
        parent::boot();

        static::created(function ($formBuilder) {
            // Jika form baru dibuat, dan ini adalah FormBuilder pertama, tambahkan 3 default fields
            $existingFields = self::where('form_id', $formBuilder->form_id)->count();

            if ($existingFields === 1) {
                // Tambahkan 3 field default
                $defaultFields = [
                    ['label' => 'Nama', 'name' => 'nama', 'type' => 'text', 'required' => true, 'order' => 1],
                    ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'required' => true, 'order' => 2],
                    ['label' => 'Nomor HP', 'name' => 'nomor_hp', 'type' => 'text', 'required' => true, 'order' => 3],
                ];

                foreach ($defaultFields as $field) {
                    if ($field['name'] !== $formBuilder->name) {
                        self::create(array_merge($field, ['form_id' => $formBuilder->form_id]));
                    }
                }
            }
        });
    }
}
