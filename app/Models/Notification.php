<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $primaryKey = 'notification_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'title', 'content', 'is_read', 'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected $casts = [
        'created_at' => 'datetime',
        // các trường khác nếu có
    ];
}
