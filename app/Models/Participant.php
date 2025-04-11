<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Participant extends Model
{
    protected $fillable = ['form_id', 'name', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    protected static function booted()
    {
        // Saat menghapus record
        static::deleting(function ($participant) {
            $formId = $participant->form_id;

            $fileFields = \App\Models\FormBuilder::where('form_id', $formId)
                ->where('type', 'file')
                ->pluck('name');

            foreach ($fileFields as $fieldName) {
                $filePath = $participant->data[$fieldName] ?? null;

                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });

        // Saat update data
        static::updating(function ($participant) {
            $originalData = $participant->getOriginal('data') ?? [];
            $newData = $participant->data ?? [];

            $formId = $participant->form_id;
            $fileFields = \App\Models\FormBuilder::where('form_id', $formId)
                ->where('type', 'file')
                ->pluck('name');

            foreach ($fileFields as $fieldName) {
                $originalFile = $originalData[$fieldName] ?? null;
                $newFile = $newData[$fieldName] ?? null;

                // Hapus file lama jika diganti
                if ($originalFile && $newFile && $originalFile !== $newFile) {
                    if (Storage::disk('public')->exists($originalFile)) {
                        Storage::disk('public')->delete($originalFile);
                    }
                }
            }
        });
    }
}
