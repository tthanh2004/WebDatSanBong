<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidUsername implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = trim((string)($value ?? ''));

        if ($value === '') {
            $this->errorCode = '2E1';
            return false;
        }
        if (strlen($value) < 4 || strlen($value) > 16) {
            $this->errorCode = '2E2';
            return false;
        }

        // kiểm tra tồn tại
        if (DB::table('users')->where('username', $value)->exists()) {
            $this->errorCode = '2E3';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '2E1' => 'Vui lòng nhập tên tài khoản (Lỗi 2E1).',
            '2E2' => 'Tên tài khoản không được vượt quá 16 ký tự (và tối thiểu 4) (Lỗi 2E2).',
            '2E3' => 'Tên tài khoản này đã được sử dụng (Lỗi 2E3).',
            default => 'Không hợp lệ.',
        };
    }
}
