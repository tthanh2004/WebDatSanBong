<?php

namespace App\Http\Requests\ValidateMaGianGia;

use Illuminate\Contracts\Validation\Rule;

class ValidTenKhuyenMai implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');//1

        if ($value === '') {//2
            $this->errorCode = '1E6';
            return false;
        }

        if (strlen($value) > 50) {//3
            $this->errorCode = '1E7';
            return false;
        }

        return true;//4
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E6' => 'Tên chương trình khuyến mãi không được để trống (Lỗi 1E6).',//5
            '1E7' => 'Tên chương trình khuyến mãi không được vượt quá 50 ký tự (Lỗi 1E7).',//6
            default => 'Tên chương trình khuyến mãi không hợp lệ.',
        };
    }
}
