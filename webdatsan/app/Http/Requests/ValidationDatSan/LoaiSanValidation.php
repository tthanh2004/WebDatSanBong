<?php

namespace App\Http\Requests\Validations;

use Illuminate\Contracts\Validation\Rule;
use App\Models\LoaiSan;

trait ValidateLoaiSan
{
    public function loaiSanRules(): array
    {
        return [
            'loai_san_id' => [new ValidLoaiSan()],      //1
        ];
    }
}

class ValidLoaiSan implements Rule
{
    protected string $errorCode = '';       //2

    public function passes($attribute, $value)
    {
        if (empty($value)) {        //3
            $this->errorCode = '1E11';
            return false;
        }

        if (!LoaiSan::where('id', $value)->exists()) {      //4
            $this->errorCode = '1E12';
            return false;
        }

        return true;        //5
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E11' => 'Loại sân không được để trống (Lỗi 1E11).',       //6
            '1E12' => 'Loại sân không tồn tại (Lỗi 1E12).',         //7
        };
    }
}
