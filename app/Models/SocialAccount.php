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
// Mối quan hệ belongsTo xác định rằng mỗi bản ghi trong bảng social_accounts chứa một khóa ngoại (user_id) trỏ đến khóa chính (id) của bảng users.
// Khi phương thức user được gọi trên một đối tượng SocialAccount, Laravel sẽ tự động tìm và trả về đối tượng User liên quan dựa trên giá trị của khóa ngoại (user_id).
// Kết quả là bạn có thể trực tiếp truy cập thông tin người dùng từ một đối tượng SocialAccount, ví dụ: $socialAccount->user.