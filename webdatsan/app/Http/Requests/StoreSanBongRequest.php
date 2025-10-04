<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSanBongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ma_san' => ['required', 'string', 'size:6', 'regex:/^SB\d{4}$/', 'unique:san_bong,ma_san'],
            'ten_san' => ['required', 'string', 'max:50'],
            'loai_san' => ['required', 'in:5,7,11'],
            'gia_thue' => ['required', 'integer', 'min:1'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'different:start_time'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->input('start_time');
            $end = $this->input('end_time');

            if ($start && $end) {
                // Nếu giờ bằng nhau thì đã check ở different
                // Trường hợp end_time < start_time => cho phép (qua ngày hôm sau)
                // => không cần báo lỗi.
                // Chỉ cần đảm bảo định dạng hợp lệ (đã có rule).
            }
        });
    }

    public function messages(): array
    {
        return [
            'ma_san.required' => 'Mã sân không được để trống.',
            'ma_san.size' => 'Mã sân phải đúng 6 ký tự.',
            'ma_san.regex' => 'Mã sân phải theo dạng SB + 4 số (VD: SB1234).',
            'ma_san.unique' => 'Mã sân đã tồn tại.',

            'ten_san.required' => 'Tên sân không được để trống.',
            'ten_san.max' => 'Tên sân tối đa 50 ký tự.',

            'loai_san.required' => 'Loại sân không được để trống.',
            'loai_san.in' => 'Loại sân chỉ được chọn 5, 7 hoặc 11 người.',

            'gia_thue.required' => 'Giá thuê không được để trống.',
            'gia_thue.integer' => 'Giá thuê phải là số nguyên.',
            'gia_thue.min' => 'Giá thuê phải lớn hơn 0.',

            'start_time.required' => 'Giờ bắt đầu không được để trống.',
            'start_time.date_format' => 'Giờ bắt đầu phải đúng định dạng HH:MM.',

            'end_time.required' => 'Giờ kết thúc không được để trống.',
            'end_time.date_format' => 'Giờ kết thúc phải đúng định dạng HH:MM.',
            'end_time.different' => 'Giờ kết thúc phải khác giờ bắt đầu.',
        ];
    }
}
