<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'ma_khach_hang' => ['required', 'string', 'size:8', 'regex:/^KH\d{6}$/', 'unique:customers,ma_khach_hang'],
        'name' => ['required', 'regex:/^[a-zA-Z\s]+$/u', 'max:40'],
        'phone' => ['required', 'regex:/^0\d{9}$/'],
        'email' => ['required', 'email:rfc,dns', 'max:254', 'unique:customers,email'],
        'address' => ['required', 'regex:/^[\pL\pN\s\.\,\-]+$/u', 'max:255'],
    ];
}

public function messages(): array
{
    return [
        'ma_khach_hang.required' => 'Mã khách hàng không được để trống.',
        'ma_khach_hang.size' => 'Mã khách hàng phải đúng 8 ký tự.',
        'ma_khach_hang.regex' => 'Mã khách hàng phải theo dạng KH + 6 số (ví dụ: KH123456).',
        'ma_khach_hang.unique' => 'Mã khách hàng đã tồn tại trong hệ thống.',

        'name.required' => 'Tên khách hàng không được để trống.',
        'name.regex' => 'Tên khách hàng chỉ được chứa chữ cái và khoảng trắng.',
        'name.max' => 'Tên khách hàng tối đa 40 ký tự.',

        'phone.required' => 'Số điện thoại không được để trống.',
        'phone.regex' => 'Số điện thoại phải gồm 10 số và bắt đầu bằng 0.',

        'email.required' => 'Email không được để trống.',
        'email.email' => 'Email không đúng định dạng.',
        'email.max' => 'Email tối đa 254 ký tự.',
        'email.unique' => 'Email đã tồn tại trong hệ thống.',

        'address.required' => 'Địa chỉ không được để trống.',
        'address.regex' => 'Địa chỉ chỉ được chứa chữ, số, dấu chấm, phẩy, gạch ngang và khoảng trắng.',
        'address.max' => 'Địa chỉ tối đa 255 ký tự.',
    ];
}

}
