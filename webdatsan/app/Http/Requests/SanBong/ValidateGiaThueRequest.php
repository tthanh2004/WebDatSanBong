<?php

namespace App\Http\Requests\SanBong;


use Illuminate\Foundation\Http\FormRequest;

class ValidateGiaThueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gia_thue' => [
                'required',
                'string', 
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $value = $this->input('gia_thue');

            if ($value === null || trim($value) === '') {
                $validator->errors()->add('gia_thue', 'Giá thuê không được để trống.');
                return;
            }

            if (preg_match('/\s/', $value)) {
                $validator->errors()->add('gia_thue', 'Giá thuê không hợp lệ, chỉ được nhập số');
                return;
            }

            if (preg_match('/[.,]/', $value)) {
                $validator->errors()->add('gia_thue', 'Giá thuê không hợp lệ, chỉ được nhập số');
                return;
            }

            if (!preg_match('/^[0-9]+$/', $value)) {
                $validator->errors()->add('gia_thue', 'Giá thuê không hợp lệ, chỉ được nhập số');
                return;
            }

            $intVal = intval($value);

            if ($intVal < 0) {
                $validator->errors()->add('gia_thue', 'Giá thuê phải lớn hơn 0');
                return;
            }

            if ($intVal == 0) {
                $validator->errors()->add('gia_thue', 'Giá thuê phải lớn hơn 0');
                return;
            }

        });
    }

    public function messages(): array
    {
        return [
            'gia_thue.required' => 'Giá thuê không được để trống.',
            'gia_thue.string' => 'Giá thuê không hợp lệ, chỉ được nhập số',
        ];
    }
}
