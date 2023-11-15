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
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    // Xác định các thuộc tính không nên xuất hiện khi mô hình được chuyển đổi thành dạng mảng hoặc JSON.
    // Ẩn các thuộc tính nhạy cảm như mật khẩu và mã nhớ.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
