<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanBong;

class SanBongSeeder extends Seeder
{
    public function run()
    {
        SanBong::create([
            'ma_san' => 'SB0001',
            'ten_san' => 'Sân bóng Mỹ Đình',
            'loai_san' => '11 người',
            'gia_thue' => 500000,
            'gio_bat_dau' => '05:00',
            'gio_ket_thuc' => '22:00',
        ]);

        SanBong::create([
            'ma_san' => 'SB0002',
            'ten_san' => 'Sân bóng Thanh Xuân',
            'loai_san' => '7 người',
            'gia_thue' => 300000,
            'gio_bat_dau' => '06:00',
            'gio_ket_thuc' => '23:00',
        ]);
    }
}
