<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;  // Sử dụng trait HasFactory để hỗ trợ tạo Factory cho model này

    protected $primaryKey = 'product_id';  // Chỉ định cột 'product_id' là khóa chính

    // Các thuộc tính có thể mass-assign (được gán hàng loạt)
    protected $fillable = [
        'product_name',  // Tên sản phẩm
        'description',   // Mô tả sản phẩm
        'image',         // Hình ảnh sản phẩm
        'price',         // Giá sản phẩm
        'category_id',   // Mã danh mục sản phẩm
        'slug',          // Slug sản phẩm (dùng cho URL)
    ];

    // Mối quan hệ với Category
    // Mỗi sản phẩm thuộc về một danh mục (Category)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Laravel sẽ dùng slug để tìm kiếm Product thay vì ID
    // Điều này có nghĩa là thay vì sử dụng ID để truy vấn sản phẩm, ta sẽ dùng slug.
    public function getRouteKeyName()
    {
        // Nếu đang ở route frontend, dùng slug
        return request()->is('product/*') ? 'slug' : 'product_id';
    }
    
    // Tự động tạo slug khi tạo hoặc cập nhật sản phẩm
    protected static function booted()
    {
        // Khi tạo mới sản phẩm
        static::creating(function ($product) {
            // Nếu slug không được cung cấp, tự động tạo slug từ tên sản phẩm
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->product_name);
            }
        });

        // Khi cập nhật sản phẩm
        static::updating(function ($product) {
            // Nếu slug không được cung cấp khi cập nhật, tự động tạo slug từ tên sản phẩm
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->product_name);
            }
        });
    }
}
