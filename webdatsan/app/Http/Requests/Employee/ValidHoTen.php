<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidHoTen implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = trim((string)($value ?? ''));

        if ($value === '') {
            $this->errorCode = '1E1';
            return false;
        }

        if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
            $this->errorCode = '1E2';
            return false;
        }

        if (mb_strlen($value) > 40) {
            $this->errorCode = '1E3';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '1E1' => 'Vui lòng nhập tên nhân viên (Lỗi 1E1).',
            '1E2' => 'Tên nhân viên không hợp lệ (Lỗi 1E2).',
            '1E3' => 'Tên nhân viên không được vượt quá 40 ký tự (Lỗi 1E3).',
            default => 'Không hợp lệ.',
        };
    }
}
