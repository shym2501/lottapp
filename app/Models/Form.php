<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Form extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'is_active',
        'is_closed',
        'auto_verify_enabled',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activate()
    {
        $this->start_date = now();
        $this->end_date = Carbon::now()->addDays(30);
        $this->status = 'paid';
        $this->save();
    }
}
