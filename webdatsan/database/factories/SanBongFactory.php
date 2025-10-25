<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SanBong;
use App\Models\LoaiSan;

/**
 * @extends Factory<\App\Models\SanBong>
 */
class SanBongFactory extends Factory
{
    protected $model = SanBong::class;

    public function definition(): array
    {
        return [
            'ten_san' => 'Sân ' . $this->faker->word(),
            'loai_san_id' => LoaiSan::factory(),
            'start_time' => '07:00',
            'end_time' => '22:00',
            'dia_chi' => $this->faker->address(),
        ];
    }
}
