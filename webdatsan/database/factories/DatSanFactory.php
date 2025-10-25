<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DatSan;
use App\Models\SanBong;

/**
 * @extends Factory<\App\Models\DatSan>
 */
class DatSanFactory extends Factory
{
    protected $model = DatSan::class;

    public function definition(): array
    {
        $start = $this->faker->time('H:i', '20:00');
        $end = date('H:i', strtotime($start . ' +1 hour'));

        return [
            'san_bong_id' => SanBong::factory(),
            'so_san' => $this->faker->numberBetween(1, 5),
            'ngay_dat' => now()->toDateString(),
            'gio_bat_dau' => $start,
            'gio_ket_thuc' => $end,
            'trang_thai' => 'confirmed',
        ];
    }
}
