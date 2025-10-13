<?php

namespace App\Http\Requests\Validations;

use Illuminate\Contracts\Validation\Rule;

/**
 * Trait gom rule & message cho email
 */
trait ValidateEmail
{
    public function emailRules(): array
    {
        return [
            'email' => ['required',         //1
            new ValidEmail()],       //2
        ];
    }

    public function emailMessages(): array
    {
        return [
            'email.required' => 'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E8).',          //3
        ];
    }
}

class ValidEmail implements Rule
{
    protected string $errorCode = '';           //4

    public function passes($attribute, $value)
    {
        $value = trim($value ?? '');                //5

        if ($value === '') {                    //6
            $this->errorCode = '1E8';
            return false;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {           //7
            $this->errorCode = '1E8';
            return false;
        }

        if (strlen($value) > 254) {                 //8
            $this->errorCode = '1E9';
            return false;
        }

        if (preg_match('/(^\.|\.\.|\.@)/', $value)) {           //9
            $this->errorCode = '1E10';
            return false;
        }

        $domain = substr(strrchr($value, "@"), 1);          //10
        if (                                            //11
            !$domain ||
            !str_contains($domain, ".") ||
            str_starts_with($domain, "-") ||
            str_ends_with($domain, "-")
        ) {
            $this->errorCode = '1E10';
            return false;
        }

        return true;            //12
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E8' => 'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E8).',         //13
            '1E9' => 'Email không hợp lệ, email phải ít hơn hoặc bằng 254 ký tự (Lỗi 1E9).',            //14
            '1E10' => 'Email không hợp lệ, vui lòng nhập đúng định dạng (Lỗi 1E10).',               //15
        };
    }
}
