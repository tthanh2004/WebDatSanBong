<?php

namespace App\Http\Requests\ValidationDatSan;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ImplicitRule;

class ValidHoTen implements Rule, ImplicitRule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');

        if ($value === '') {
            $this->errorCode = '1E1';
            return false;
        }

        if (!preg_match('/^(?!.*\s{2,})(?!\s)(?!.*\s$)[\p{L}\s]+$/u', $value)) {
            $this->errorCode = '1E2';
            return false;
        }

        if (mb_strlen($value) > 40) {
            $this->errorCode = '1E3';
            return false;
        }

        return true;
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E1' => 'Họ tên không được để trống (Lỗi 1E1).',
            '1E2' => 'Họ tên không hợp lệ (Lỗi 1E2).',
            '1E3' => 'Họ tên không hợp lệ, họ tên phải ít hơn hoặc bằng 40 ký tự (Lỗi 1E3).',
        };
    }
}
