<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = "payment_id";

    protected $fillable = [
        'method',
        'status',
        'order_id'
    ];

    protected $casts = [
        'method' => 'string',
        'status' => 'string',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
