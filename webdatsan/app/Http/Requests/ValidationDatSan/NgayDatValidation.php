<?php

namespace App\Http\Requests\Validations;

use Illuminate\Contracts\Validation\Rule;

/**
 * Trait gom rule kiểm tra ngày đặt sân
 */
trait ValidateNgayDat
{
    public function ngayDatRules(): array
    {
        return [
            'ngay_dat' => ['required',      //1
            'date', new ValidNgayDat()],            //2
        ];
    }

    public function ngayDatMessages(): array
    {
        return [
            'ngay_dat.required' => 'Vui lòng chọn thời gian (Lỗi 1E16).',           //3
        ];
    }
}

class ValidNgayDat implements Rule
{
    protected string $errorCode = '';           //4

    public function passes($attribute, $value)
    {
        $today = now()->toDateString();             //5

        if (empty($value)) {                //6
            $this->errorCode = '1E16';
            return false;
        }

        if ($value < $today) {          //7
            $this->errorCode = '1E17';
            return false;
        }

        return true;            //8
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E17' => 'Thời gian đặt sân không hợp lệ (Lỗi 1E17).',         //9
        };
    }
}
