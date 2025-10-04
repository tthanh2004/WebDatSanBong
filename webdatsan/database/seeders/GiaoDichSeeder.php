<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GiaoDich;
use App\Models\User;
use App\Models\SanBong;
use Carbon\Carbon;

class GiaoDichSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // lấy 1 user có sẵn
        $san  = SanBong::first(); // lấy 1 sân có sẵn

        if (!$user || !$san) {
            $this->command->error('⚠️ Chưa có user hoặc sân bóng để tạo giao dịch.');
            return;
        }

        $trangThai = ['pending', 'success', 'canceled'];

        foreach (range(1, 10) as $i) {
            GiaoDich::create([
                'user_id'       => $user->_id,
                'san_id'        => $san->_id,
                'so_tien'       => $san->gia_thue,
                'trang_thai'    => $trangThai[array_rand($trangThai)],
                'phuong_thuc'   => fake()->randomElement(['momo', 'vnpay', 'tien_mat']),
                'ngay_giao_dich'=> Carbon::now()->subDays(rand(0, 10))
            ]);
        }

        $this->command->info('✅ Đã tạo 10 giao dịch mẫu.');
    }
}
