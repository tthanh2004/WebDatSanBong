<?php

namespace App\Http\Requests\ValidationDatSan;

use Illuminate\Contracts\Validation\Rule;

trait ValidEmail
{
    public function emailRules(): array
    {
        return [
            'email' => [
                'required',
                new ValidateEmail(),
            ],
        ];
    }

    public function emailMessages(): array
    {
        return [
            'email.required' => 'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E8).',
        ];
    }
}

class ValidateEmail implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');

        // E8: rỗng
        if ($value === '') {
            $this->errorCode = '1E8';
            return false;
        }

        // E9: quá 254 ký tự (toàn chuỗi địa chỉ)
        if (strlen($value) > 254) {
            $this->errorCode = '1E9';
            return false;
        }

        // E10: local-part lỗi chấm (bắt đầu bằng '.', có '..', hoặc '.@')
        if (preg_match('/(^\.|\.{2,}|\.@)/', $value)) {
            $this->errorCode = '1E10';
            return false;
        }

        // Kiểm tra có '@' (không có => E8)
        $atPos = strrpos($value, '@');
        if ($atPos === false) {
            $this->errorCode = '1E8';
            return false;
        }

        // E10: domain thô sai
        $domain = substr($value, $atPos + 1);

        // Domain phải có dấu chấm
        if ($domain === '' || !str_contains($domain, '.')) {
            $this->errorCode = '1E10';
            return false;
        }

        // Kiểm tra từng label (chuẩn DNS)
        $labels = explode('.', $domain);
        foreach ($labels as $label) {
            // label không rỗng, 1..63 ký tự
            if ($label === '' || strlen($label) > 63) {
                $this->errorCode = '1E10';
                return false;
            }
            // không bắt đầu/kết thúc bằng '-'
            if (str_starts_with($label, '-') || str_ends_with($label, '-')) {
                $this->errorCode = '1E10';
                return false;
            }
            // chỉ cho [A-Za-z0-9-]
            if (!preg_match('/^[A-Za-z0-9-]+$/', $label)) {
                $this->errorCode = '1E10';
                return false;
            }
        }

        // TLD tối thiểu 2 ký tự
        $tld = end($labels);
        if (strlen($tld) < 2) {
            $this->errorCode = '1E10';
            return false;
        }

        // E8: kiểm tra tổng thể cuối cùng
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errorCode = '1E8';
            return false;
        }

        return true;
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E8' => 'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E8).',
            '1E9' => 'Email không hợp lệ, email phải ít hơn hoặc bằng 254 ký tự (Lỗi 1E9).',
            '1E10' => 'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E10).',
            default => 'Email không hợp lệ.',
        };
    }
}
