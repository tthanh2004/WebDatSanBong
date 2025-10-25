<?php

namespace App\Http\Requests\SanBong;


use Illuminate\Foundation\Http\FormRequest;

class ValidateTenSanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ten_san' => [
                'required',
                'string',
                'max:50',
                'regex:/^[\p{L}\p{N}\s\-\',\.]+$/u',
            ],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('ten_san')) {
            $value = (string) $this->input('ten_san');

            // Loại bỏ ký tự điều khiển (tab, newline, v.v.)
            $cleaned = preg_replace('/[\p{C}]+/u', '', $value);

            // Loại bỏ khoảng trắng thừa ở đầu, cuối và chuẩn hoá khoảng trắng
            $cleaned = trim(preg_replace('/\s+/u', ' ', $cleaned));

            $this->merge(['ten_san' => $cleaned]);
        }
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $value = $this->input('ten_san');

            // Nếu rỗng hoặc chỉ toàn khoảng trắng
            if ($value === null || trim($value) === '') {
                $validator->errors()->add('ten_san', 'Tên sân không được để trống');
                return;
            }

            // Kiểm tra độ dài (phân tích giá trị biên)
            $len = mb_strlen($value, 'UTF-8');
            if ($len < 1) {
                $validator->errors()->add('ten_san', 'Tên sân không được để trống');
                return;
            }
            if ($len > 50) {
                $validator->errors()->add('ten_san', 'Tên sân vượt quá 50 ký tự');
                return;
            }

            // Kiểm tra ký tự điều khiển hoặc emoji
            if (preg_match('/[\p{C}\x{1F300}-\x{1F6FF}\x{1F900}-\x{1F9FF}\x{2600}-\x{26FF}]/u', $value)) {
                $validator->errors()->add('ten_san', 'Tên sân không hợp lệ');
                return;
            }

            // Kiểm tra ký tự không hợp lệ (ngoài chữ, số, khoảng trắng, -, ', .)
            if (preg_match('/[^\p{L}\p{N}\s\-\',\.]/u', $value)) {
                $validator->errors()->add('ten_san', 'Tên sân không hợp lệ');
                return;
            }
        });
    }

    public function messages(): array
{
    return [
        'ten_san.required' => 'Tên sân không được để trống',
        'ten_san.string'   => 'Tên sân không hợp lệ',
        'ten_san.max'      => 'Tên sân vượt quá 50 ký tự',
        // Đổi message regex để khớp test:
        'ten_san.regex'    => 'Tên sân không hợp lệ',
    ];
}

}
