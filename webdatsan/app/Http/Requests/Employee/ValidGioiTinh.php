<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidGioiTinh implements Rule
{
    public function passes($attribute, $value): bool
    {
        $value = (string)($value ?? '');
        return $value !== '' && in_array($value, ['Nam', 'Nữ'], true);
    }

    public function message(): string
    {
        return 'Vui lòng chọn giới tính nhân viên (Lỗi 1E4).';
    }
}
