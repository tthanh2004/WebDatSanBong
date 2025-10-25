<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidSoDienThoai implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = trim((string)($value ?? ''));

        if ($value === '') {
            $this->errorCode = '1E7';
            return false;
        }
        if (!preg_match('/^\d+$/', $value)) {
            $this->errorCode = '1E8';
            return false;
        }
        $len = strlen($value);
        if ($len !== 10 && $len !== 11) {
            $this->errorCode = '1E9';
            return false;
        }
        if ($value[0] !== '0') {
            $this->errorCode = '1E10';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '1E7'  => 'Vui lòng nhập số điện thoại (Lỗi 1E7).',
            '1E8'  => 'Số điện thoại chỉ được chứa số (Lỗi 1E8).',
            '1E9'  => 'Số điện thoại phải gồm 10 hoặc 11 số (Lỗi 1E9).',
            '1E10' => 'Số điện thoại phải bắt đầu bằng số 0 (Lỗi 1E10).',
            default => 'Không hợp lệ.',
        };
    }
}
