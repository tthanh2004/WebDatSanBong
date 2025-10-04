<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Customer::create([
                'ma_khach_hang' => 'KH' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'name' => "Khách Hàng $i",
                'phone' => '0' . rand(100000000, 999999999),
                'email' => "khach$i@example.com",
                'address' => "Địa chỉ số $i, Quận $i, TP HCM",
            ]);
        }
    }
}
