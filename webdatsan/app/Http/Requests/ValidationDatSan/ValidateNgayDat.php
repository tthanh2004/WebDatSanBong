<?php

namespace App\Http\Requests\ValidationDatSan;

use Illuminate\Contracts\Validation\Rule;

class ValidateNgayDat implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $today = now()->toDateString();

        if ($value < $today) {
            $this->errorCode = '1E17';
            return false;
        }

        return true;
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E17' => 'Thời gian đặt sân không hợp lệ (Lỗi 1E17).',
        };
    }
}
