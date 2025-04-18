<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    protected $fillable = ['form_id', 'participant_id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
