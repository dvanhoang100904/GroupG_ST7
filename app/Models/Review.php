<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $primaryKey = 'review_id';

    protected $fillable = [
        'user_id',
        'product_id',
        'content',     // ✅ KHÔNG PHẢI description
        'photo',
        'rating',      // ✅ KHÔNG PHẢI star_rating
        'type',
        'chat_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function replies()
    {
        return $this->hasMany(Review::class, 'chat_id')->where('type', 'reply');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
