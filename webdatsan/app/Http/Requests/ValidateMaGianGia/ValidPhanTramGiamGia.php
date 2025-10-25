<?php

namespace App\Http\Requests\ValidateMaGianGia;

use Illuminate\Contracts\Validation\Rule;

class ValidPhanTramGiamGia implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');//1

        if ($value === '') {//2
            $this->errorCode = '1E10';
            return false;
        }

        if (!is_numeric($value)) {//3
            $this->errorCode = '1E11';
            return false;
        }

        if ($value < 0) {//4
            $this->errorCode = '1E12';
            return false;
        }

        if ($value > 100) {//5
            $this->errorCode = '1E13';
            return false;
        }

        return true;//6
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E10' => 'Phần trăm giảm giá không được để trống (Lỗi 1E10).',//7
            '1E11' => 'Phần trăm giảm giá phải là số (Lỗi 1E11).',//8
            '1E12' => 'Phần trăm giảm giá không hợp lệ, phải ≥ 0 (Lỗi 1E12).',//9
            '1E13' => 'Phần trăm giảm giá không hợp lệ, phải ≤ 100 (Lỗi 1E13).',//10
            default => 'Phần trăm giảm giá không hợp lệ.',
        };
    }
}
