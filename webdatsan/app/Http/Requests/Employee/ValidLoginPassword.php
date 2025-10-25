<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidLoginPassword implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = (string)($value ?? '');

        if ($value === '') {
            $this->errorCode = '3E5';
            return false;
        }
        if (strlen($value) < 6) {
            $this->errorCode = '3E6';
            return false;
        }
        if (strlen($value) > 20) {
            $this->errorCode = '3E7';
            return false;
        }
        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '3E5' => 'Tên đăng nhập hoặc mật khẩu không được để trống (Lỗi 3E5).',
            '3E6' => 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E6).',
            '3E7' => 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E7).',
            default => 'Không hợp lệ.',
        };
    }
}
