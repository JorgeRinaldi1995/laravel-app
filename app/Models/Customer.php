<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Customer extends User
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id', 'surname', 'gender', 'birthdate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}