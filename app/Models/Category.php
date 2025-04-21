<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Tên bảng
    protected $primaryKey = 'category_id'; // Khóa chính

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['category_name', 'slug', 'description'];

    // Quan hệ: 1 danh mục có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
