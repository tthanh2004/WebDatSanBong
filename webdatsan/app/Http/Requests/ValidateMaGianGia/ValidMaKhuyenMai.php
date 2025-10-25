<?php

namespace App\Http\Requests\ValidateMaGianGia;

use Illuminate\Contracts\Validation\Rule;

class ValidMaKhuyenMai implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $value = trim($value ?? ''); //1
       
        if (!preg_match('/^[A-Za-z0-9]+$/', $value)) {//2
            $this->errorCode = '1E1';
            return false;
        }
      
        if (strlen($value) != 5) {//3
            $this->errorCode = '1E2';
            return false;
        }
     
        if (substr($value, 0, 2) !== 'KM') {//4
            $this->errorCode = '1E3';
            return false;
        }
    
        if (!preg_match('/^[0-9]{3}$/', substr($value, 2))) {//5
            $this->errorCode = '1E4';
            return false;
        }
        return true;//6
    }
    public function message()
    {
        return match ($this->errorCode) {
            '1E1' => 'Mã chương trình khuyến mãi không được chứa khoảng trắng hoặc ký tự đặc biệt (Lỗi 1E1).',//7
            '1E2' => 'Mã chương trình khuyến mãi phải có đúng 5 ký tự (Lỗi 1E2).',//8
            '1E3' => 'Mã chương trình khuyến mãi phải bắt đầu bằng “KM” (Lỗi 1E3).',//9
            '1E4' => '3 ký tự cuối của mã phải là chữ số (Lỗi 1E4).',//10
            default => 'Mã chương trình khuyến mãi không hợp lệ.',
        };
    }
}
