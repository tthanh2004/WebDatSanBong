<?php

namespace App\Http\Requests\Employee;

use Illuminate\Contracts\Validation\Rule;

class ValidNgaySinh implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value): bool
    {
        $value = (string)($value ?? '');

        if ($value === '') {
            $this->errorCode = '1E5';
            return false;
        }

        // chấp nhận dd/mm/yyyy hoặc yyyy-mm-dd, chuẩn hóa về yyyy-mm-dd
        $norm = str_replace('/', '-', $value);

        try {
            $dob   = new \DateTime($norm);
            $today = new \DateTime('today');

            // nếu người dùng nhập bừa (vd 2025-99-99) DateTime sẽ ném Exception
            // hoặc tạo ngày không hợp lệ -> ta coi như 1E5
            if ($dob > $today) {
                $this->errorCode = '1E5';
                return false;
            }

            $age = $today->diff($dob)->y;

            if ($age < 18) {
                $this->errorCode = '1E6';
                return false;
            }
        } catch (\Throwable $e) {
            $this->errorCode = '1E5';
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return match ($this->errorCode) {
            '1E5' => 'Vui lòng nhập ngày sinh (định dạng yyyy/mm/dd) (Lỗi 1E5).',
            '1E6' => 'Độ tuổi không hợp lệ (phải ≥ 18) (Lỗi 1E6).',
            default => 'Không hợp lệ.',
        };
    }
}
