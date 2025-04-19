<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Form;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function form()
    {
        return $this->hasOne(Form::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            // Buat 1 form default
            $form = $user->form()->create([
                'title' => 'Form - ' . $user->name,
                'user_id' => $user->id,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'status' => 'pending',
            ]);

            // Buat 3 field default
            $defaultFields = [
                ['label' => 'Nama Lengkap', 'name' => 'fullname', 'type' => 'text', 'is_required' => true, 'position' => 1],
                ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'is_required' => true, 'position' => 2],
                ['label' => 'Nomor HP/WA', 'name' => 'contact-person', 'type' => 'number', 'is_required' => true, 'position' => 3],
            ];

            foreach ($defaultFields as $field) {
                \App\Models\FormBuilder::create(array_merge($field, ['form_id' => $form->id]));
            }
        });
    }
}
