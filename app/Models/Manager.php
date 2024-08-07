<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Manager extends User
{
    use HasFactory, Notifiable;

    public function productsOwnership()
    {
        return $this->hasMany(Product::class);
    }

}