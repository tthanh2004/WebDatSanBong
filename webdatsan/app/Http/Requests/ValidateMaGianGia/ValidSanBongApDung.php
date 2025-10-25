<?php

namespace App\Http\Requests\ValidateMaGianGia;

use Illuminate\Contracts\Validation\Rule;

class ValidSanBongApDung implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        if (empty($value)) {//1
            $this->errorCode = '1E8';
            return false;
        }

        if (is_array($value)) {//2
            foreach ($value as $san) {//3
                if (str_contains(strtolower($san), 'bảo trì') || str_contains(strtolower($san), 'khóa')) {//4
                    $this->errorCode = '1E9';
                    return false;
                }
            }
        }

        return true;//5
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E8' => 'Phải chọn ít nhất 1 sân bóng (Lỗi 1E8).',//6
            '1E9' => 'Sân bóng không hợp lệ, sân bóng đang bảo trì hoặc bị khóa (Lỗi 1E9).',//7
            
            default => 'Sân bóng không hợp lệ.',
        };
    }
}
