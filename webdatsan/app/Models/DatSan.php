<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class DatSan extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'dat_san';

    protected $fillable = [
        'user_id',        // Khách hàng đặt sân
        'ho_ten',
        'so_dien_thoai',
        'email',
        'ma_khach_hang',  // Lấy từ DB user
        'san_bong_id',    // Mã sân
        'ten_san',
        'loai_san',
        'ngay_dat',
        'gio_bat_dau',
        'gio_ket_thuc',
        'trang_thai',     // pending / success / cancelled
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function sanBong()
    {
        return $this->belongsTo(SanBong::class, 'san_bong_id', '_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'ma_khach_hang', 'ma_khach_hang');
    }
    
}

