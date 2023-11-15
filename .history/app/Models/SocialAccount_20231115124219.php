<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use HasFactory;
    protected $table = 'social_accounts';
    protected $fillable = [
        'user_id',
        'social_id',
        'social_provider',
        'social_name',
    ];

    public function user()

    {
        // Xác định rằng một bản ghi trong bảng social_accounts thuộc về một bản ghi trong bảng users.
        // Thiết lập mối quan hệ "nhiều social_accounts thuộc về một user" (many-to-one).
        return $this->belongsTo(User::class);
    }
}
