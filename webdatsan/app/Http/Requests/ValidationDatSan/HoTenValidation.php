<?php

namespace App\Http\Requests\Validations;

use Illuminate\Contracts\Validation\Rule;

trait ValidateHoTen
{
    public function hoTenRules(): array
    {
        return [
            'ho_ten' => new ValidHoTen(),       //1
        ];
    }
}

class ValidHoTen implements Rule
{
    protected string $errorCode = '';       //2

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');        //3

        if ($value === '') {                //4
            $this->errorCode = '1E1';
            return false;
        }
        if (!preg_match('/^(?!.*\s{2,})(?!\s)(?!.*\s$)[\p{L}\s]+$/u', $value)) {            //5
            $this->errorCode = '1E2';
            return false;
        }

        if (mb_strlen($value) > 40) {               //6
            $this->errorCode = '1E3';
            return false;
        }

        return true;                //7
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E1' => 'Họ tên không được để trống (Lỗi 1E1).',           //8
            '1E2' => 'Họ tên không hợp lệ (Lỗi 1E2).',              //9
            '1E3' => 'Họ tên không hợp lệ, họ tên phải ít hơn hoặc bằng 40 ký tự (Lỗi 1E3).',           //10
        };
    }
}
