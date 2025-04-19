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

    protected static function booted()
    {
        static::creating(function ($coupon) {
            if (empty($coupon->number_type)) {
                $coupon->number_type = 'sequential';
            }
        });

        static::updating(function ($coupon) {
            if (empty($coupon->number_type)) {
                $coupon->number_type = 'sequential';
            }
            // Jika kupon sebelumnya aktif dan sekarang dinonaktifkan
            if ($coupon->isDirty('is_active') && $coupon->is_active == false) {
                // Hapus kode kupon dari peserta yang terkait dengan form ini
                \App\Models\Participant::where('form_id', $coupon->form_id)
                    ->update(['kode_kupon' => null]);
            }
        });
    }
}
