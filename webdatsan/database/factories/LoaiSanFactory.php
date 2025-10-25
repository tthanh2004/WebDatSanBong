<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LoaiSan;

/**
 * @extends Factory<\App\Models\LoaiSan>
 */
class LoaiSanFactory extends Factory
{
    protected $model = LoaiSan::class;

    public function definition(): array
    {
        return [
            'ten_loai' => $this->faker->words(2, true),
            'gia_san' => $this->faker->numberBetween(100000, 500000),
        ];
    }
}
