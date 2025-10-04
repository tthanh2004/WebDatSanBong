<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\ThanhToan;
use App\Models\DatSan;

class Customer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'customers';
    protected $fillable = ['ma_khach_hang','name','phone','email','address'];

    public function bookings()
    {
        return $this->hasMany(DatSan::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(ThanhToan::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }
}