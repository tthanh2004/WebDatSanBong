<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class SanBong extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'san_bong';

        protected $fillable = [
        'ma_san',
        'ten_san',
        'loai_san',
        'gia_thue',
        'start_time',
        'end_time',
        'status',
    ];
}
