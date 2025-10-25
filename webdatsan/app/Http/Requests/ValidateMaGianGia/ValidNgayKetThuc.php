<?php
namespace App\Http\Requests\ValidateMaGianGia;

use Illuminate\Contracts\Validation\Rule;

class ValidNgayKetThuc implements Rule
{
    protected string $errorCode = '';
    protected $ngayBatDau;

    public function __construct($ngayBatDau)
    {
        $this->ngayBatDau = $ngayBatDau;
    }

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');//1

        if ($value === '') {//2
            $this->errorCode = '1E17';
            return false;
        }

        if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {//3
            $this->errorCode = '1E18';
            return false;
        }

        $start = strtotime(str_replace('/', '-', $this->ngayBatDau));//4
        $end = strtotime(str_replace('/', '-', $value));

        if ($end < $start) {//5
            $this->errorCode = '1E19';
            return false;
        }

        return true;//6
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E17' => 'Ngày kết thúc không được để trống (Lỗi 1E17).',//7
            '1E18' => 'Định dạng ngày kết thúc không hợp lệ (Lỗi 1E18).',//8
            '1E19' => 'Ngày kết thúc không hợp lệ, phải lớn hơn hoặc bằng ngày bắt đầu (Lỗi 1E19).',//9
            default => 'Ngày kết thúc không hợp lệ.',
        };
    }
}
