<?php

namespace App\Http\Requests\ValidationDatSan;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ImplicitRule;
use App\Models\LoaiSan;

class ValidLoaiSan implements Rule, ImplicitRule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        // Quan trọng: trim để "   " thành chuỗi rỗng
        $value = is_string($value) ? trim($value) : $value;

        // E11: rỗng hoặc null
        if ($value === '' || $value === null) {
            $this->errorCode = '1E11';
            return false;
        }

        // E12: không tồn tại trong bảng LoaiSan
        // (Trong test bạn mock alias LoaiSan::where()->exists() để không cần DB)
        if (!LoaiSan::where('id', $value)->exists()) {
            $this->errorCode = '1E12';
            return false;
        }

        return true;
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E11' => 'Loại sân không được để trống (Lỗi 1E11).',
            '1E12' => 'Loại sân không tồn tại (Lỗi 1E12).',
        };
    }
}
