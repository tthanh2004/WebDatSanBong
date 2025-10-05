<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\SanBong;
use App\Models\Customer;

class StoreThanhToanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'ma_san'      => 'required|string',
            'so_tien'     => 'required|numeric',
            'phuong_thuc' => 'required|in:Tiền mặt,Chuyển khoản,Ví điện tử',
        ];

        if (auth()->user()->role !== 'admin') {
            $rules['ma_khach_hang'] = 'required|string';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check mã sân có tồn tại không
            if (!SanBong::where('ma_san', $this->ma_san)->exists()) {
                $validator->errors()->add('ma_san', 'Mã sân không tồn tại trong hệ thống (Mã lỗi: 4E2).');
            }

            // Nếu không phải admin thì check khách hàng
            if (auth()->user()->role !== 'admin') {
                if (!Customer::where('ma_khach_hang', $this->ma_khach_hang)->exists()) {
                    $validator->errors()->add('ma_khach_hang', 'Khách hàng không tồn tại (Mã lỗi: 4E1).');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'ma_khach_hang.required' => 'Mã khách hàng là bắt buộc.',
            'ma_san.required'        => 'Mã sân là bắt buộc.',
            'so_tien.required'       => 'Số tiền thanh toán không được để trống.',
            'so_tien.numeric'        => 'Số tiền thanh toán phải là số.',
            'phuong_thuc.required'   => 'Phương thức thanh toán là bắt buộc.',
            'phuong_thuc.in'         => 'Phương thức thanh toán không hợp lệ.',
        ];
    }
}
