<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidPassword implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = (string)($value ?? '');

        if ($value === '') {
            $this->errorCode = '2E4';
            return false;
        }
        if (strlen($value) < 6) {
            $this->errorCode = '2E5';
            return false;
        }
        if (strlen($value) > 20) {
            $this->errorCode = '2E6';
            return false;
        }
        if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).+$/', $value)) {
            $this->errorCode = '2E7';
            return false;
        }
        if (preg_match('/\s/', $value)) {
            $this->errorCode = '2E8';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '2E4' => 'Vui lòng nhập mật khẩu (Lỗi 2E4).',
            '2E5' => 'Mật khẩu phải có ít nhất 6 ký tự (Lỗi 2E5).',
            '2E6' => 'Mật khẩu không được vượt quá 20 ký tự (Lỗi 2E6).',
            '2E7' => 'Mật khẩu phải bao gồm cả chữ và số (Lỗi 2E7).',
            '2E8' => 'Mật khẩu không được chứa khoảng trắng (Lỗi 2E8).',
            default => 'Không hợp lệ.',
        };
    }
}
