<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidLoginUsername implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = trim((string)($value ?? ''));

        if ($value === '') {
            $this->errorCode = '3E1';
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $this->errorCode = '3E2';
            return false;
        }
        if (strlen($value) < 4 || strlen($value) > 16) {
            $this->errorCode = '3E3';
            return false;
        }
        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '3E1' => 'Tên đăng nhập hoặc mật khẩu không được để trống (Lỗi 3E1).',
            '3E2' => 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E2).',
            '3E3' => 'Tài khoản hoặc mật khẩu không chính xác (Lỗi 3E3).',
            default => 'Không hợp lệ.',
        };
    }
}
