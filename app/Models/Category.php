<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;


    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'description',
        'image',
        'slug',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Tạo slug tự động nếu không có
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name) . '-' . uniqid();
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
