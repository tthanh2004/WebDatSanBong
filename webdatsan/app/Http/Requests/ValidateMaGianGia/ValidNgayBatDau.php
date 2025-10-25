<?php

namespace App\Http\Requests\ValidateMaGianGia;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class ValidNgayBatDau implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');

        // Rỗng
        if ($value === '') {
            $this->errorCode = '1E14';
            return false;
        }

        // Định dạng d/m/Y (bắt buộc đúng, không tự “đoán”)
        try {
            $date = Carbon::createFromFormat('d/m/Y', $value)->startOfDay();
        } catch (\Throwable $e) {
            $this->errorCode = '1E15';
            return false;
        }
        // Bắt lỗi các chuỗi sai nhưng đôi khi vẫn parse được (đảm bảo round-trip)
        if ($date->format('d/m/Y') !== $value) {
            $this->errorCode = '1E15';
            return false;
        }

        // Không được trong quá khứ (so với hôm nay)
        $today = Carbon::today();
        if ($date->lt($today)) {
            $this->errorCode = '1E16';
            return false;
        }

        return true;
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E14' => 'Ngày bắt đầu không được để trống (Lỗi 1E14).',
            '1E15' => 'Định dạng ngày bắt đầu không hợp lệ (Lỗi 1E15).',
            '1E16' => 'Ngày bắt đầu không hợp lệ, không được là ngày trong quá khứ (Lỗi 1E16).',
            default => 'Ngày bắt đầu không hợp lệ.',
        };
    }
}
