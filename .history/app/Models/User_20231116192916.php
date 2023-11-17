<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    // protected $fillable = [
    //     // Only the fields listed in the $fillable array can be assigned via the create or update methods with user input.
    //     'name',
    //     'email',
    //     'password',
    // ];
    
    // protected $hidden = [
    //     // Hides sensitive attributes such as passwords and remember tokens.
    //     'password',
    //     'remember_token',
    // ];

    // protected $casts = [
    //     // Converts the data type of fields, for example, casting email_verified_at to the datetime type and password to the hashed type.
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed',
    // ];
}
