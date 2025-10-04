<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ThanhToan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'thanh_toan';

    protected $fillable = [
        'user_id', 'san_bong_id', 'so_tien', 'trang_thai', 'phuong_thuc', 'ngay_thanh_toan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function sanBong()
    {
        return $this->belongsTo(SanBong::class, 'san_bong_id');
    }
}
