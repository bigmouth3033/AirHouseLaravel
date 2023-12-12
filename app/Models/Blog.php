<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Blog extends Model
{
    use HasFactory;
    protected $table = 'blogs';

    public function categories(): HasMany
    {
        return $this->hasMany(BlogOfCate::class, 'id_blog', 'id_blog_categories');
    }
}
