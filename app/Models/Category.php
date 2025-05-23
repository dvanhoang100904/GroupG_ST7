<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'category_name',
        'description',
        'image',
        'slug',
    ];

    // Quan hệ: 1 danh mục có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Tạo slug tự động nếu không có
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->category_name) . '-' . uniqid();
            }
        });
    }
}
