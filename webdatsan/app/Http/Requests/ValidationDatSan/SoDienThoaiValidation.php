<?php

namespace App\Http\Requests\Validations;

use Illuminate\Contracts\Validation\Rule;

/**
 * Trait gom rule & message cho số điện thoại
 */
trait ValidateSoDienThoai
{
    public function soDienThoaiRules(): array
    {
        return [
            'so_dien_thoai' => 'required',      //1
            new ValidSoDienThoai(),             //2
        ];
    }

    public function soDienThoaiMessages(): array
    {
        return [
            'so_dien_thoai.required' => 'Số điện thoại không được để trống (Lỗi 1E4).',     //3
        ];
    }
}

class ValidSoDienThoai implements Rule
{
    protected string $errorCode = '';       //4

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');        //5

        if (!preg_match('/^[0-9]+$/', $value)) {            //6
            $this->errorCode = '1E5';
            return false;
        }

        if (strlen($value) !== 10) {                        //7
            $this->errorCode = '1E6';
            return false;
        }

        if ($value[0] !== '0') {                            //8
            $this->errorCode = '1E7';
            return false;
        }

        return true;                                //9
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E5' => 'Số điện thoại không hợp lệ, số điện thoại không được chứa các ký tự đặc biệt hoặc khoảng trắng (Lỗi 1E5).',       //10
            '1E6' => 'Số điện thoại không hợp lệ, phải có đúng 10 chữ số (Lỗi 1E6).',           //11
            '1E7' => 'Số điện thoại phải bắt đầu bằng số 0 (Lỗi 1E7).',             //12
        };
    }
}
