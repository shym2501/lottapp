<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Participant extends Model
{
    protected $fillable = ['form_id', 'name', 'data', 'kode_kupon'];

    protected $casts = [
        'data' => 'array',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }

    protected static function booted()
    {
        // Saat membuat data participant
        static::creating(function ($participant) {
            // Ambil form berdasarkan form_id langsung
            $form = \App\Models\Form::find($participant->form_id);

            if (!$form) {
                return;
            }

            // Ambil coupon yang aktif untuk form ini
            $coupon = \App\Models\Coupon::where('form_id', $form->id)->where('is_active', true)->first();

            if ($coupon) {
                $prefix = $coupon->use_prefix ? ($coupon->prefix . '-') : '';
                $digitLength = $coupon->getDigitLength();
                $maxKupon = pow(10, $digitLength) - 1;

                // Tipe terurut
                if ($coupon->number_type === 'sequential') {
                    $usedNumbers = Participant::where('form_id', $form->id)
                        ->whereNotNull('kode_kupon')
                        ->pluck('kode_kupon')
                        ->map(function ($kode) use ($prefix) {
                            return (int) str_replace($prefix, '', $kode);
                        })
                        ->toArray();

                    for ($i = 1; $i <= $maxKupon; $i++) {
                        if (!in_array($i, $usedNumbers)) {
                            $number = str_pad($i, $digitLength, '0', STR_PAD_LEFT);
                            $participant->kode_kupon = $prefix . $number;
                            break;
                        }
                    }
                } else {
                    // Tipe acak: loop sampai dapat kode unik
                    do {
                        $randomCode = strtoupper(Str::random(6));
                        $fullCode = $prefix . $randomCode;
                    } while (Participant::where('kode_kupon', $fullCode)->exists());

                    $participant->kode_kupon = $fullCode;
                }
            }
        });

        // Hapus Image - Saat menghapus record
        static::deleting(function ($participant) {
            $formId = $participant->form_id;

            $fileFields = \App\Models\FormBuilder::where('form_id', $formId)
                ->where('type', 'file')
                ->pluck('name');

            foreach ($fileFields as $fieldName) {
                $filePath = $participant->data[$fieldName]['value'] ?? $participant->data[$fieldName] ?? null;

                if (is_string($filePath) && Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        });

        // Update Image - Saat update data
        static::updating(function ($participant) {
            $originalData = $participant->getOriginal('data') ?? [];
            $newData = $participant->data ?? [];

            $formId = $participant->form_id;
            $fileFields = \App\Models\FormBuilder::where('form_id', $formId)
                ->where('type', 'file')
                ->pluck('name');

            foreach ($fileFields as $fieldName) {
                $originalFile = $originalData[$fieldName]['value'] ?? $originalData[$fieldName] ?? null;
                $newFile = $newData[$fieldName]['value'] ?? $newData[$fieldName] ?? null;

                // Hapus file lama jika diganti
                if ($originalFile && $newFile) {
                    $originalPath = is_array($originalFile) ? $originalFile['value'] ?? null : $originalFile;
                    $newPath = is_array($newFile) ? $newFile['value'] ?? null : $newFile;

                    if (
                        $originalPath &&
                        $newPath &&
                        $originalPath !== $newPath &&
                        is_string($originalPath) &&
                        Storage::disk('public')->exists($originalPath)
                    ) {
                        Storage::disk('public')->delete($originalPath);
                    }
                }
            }
        });
    }
}
