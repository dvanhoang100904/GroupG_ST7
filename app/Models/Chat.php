<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'receiver_id',
        'description',
        'type',
        'status',
        'assessment_star_id',
        'photo',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Chat::class, 'receiver_id', 'user_id')
            ->where('type', 'reply');
    }
}
