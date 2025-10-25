<?php

namespace App\Http\Requests\SanBong;


use Illuminate\Foundation\Http\FormRequest;

class ValidateGioHoatDongRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gio_bat_dau' => ['required', 'string'],
            'gio_ket_thuc' => ['required', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->input('gio_bat_dau');
            $end = $this->input('gio_ket_thuc');

            // ===== 1️⃣ Kiểm tra rỗng =====
            if ($start === null || trim($start) === '' || $end === null || trim($end) === '') {
                $validator->errors()->add('gio_hoat_dong', 'Giờ hoạt động không hợp lệ, vui lòng nhập đúng định dạng hh:mm');
                return;
            }

            // ===== 2️⃣ Kiểm tra định dạng HH:mm =====
            $pattern = '/^(?:[01]\d|2[0-3]):[0-5]\d$/';
            if (!preg_match($pattern, $start) || !preg_match($pattern, $end)) {
                $validator->errors()->add('gio_hoat_dong', 'Giờ hoạt động không hợp lệ, vui lòng nhập đúng định dạng hh:mm');
                return;
            }

            // ===== 3️⃣ Chuyển đổi sang phút để so sánh =====
            [$sh, $sm] = explode(':', $start);
            [$eh, $em] = explode(':', $end);
            $startMinutes = intval($sh) * 60 + intval($sm);
            $endMinutes = intval($eh) * 60 + intval($em);

            // ===== 4️⃣ Kiểm tra trùng nhau =====
            if ($startMinutes === $endMinutes) {
                $validator->errors()->add('gio_hoat_dong', 'Giờ hoạt động không hợp lệ');
                return;
            }

            // ===== 5️⃣ Kiểm tra cùng ngày / qua đêm =====
            if ($endMinutes > $startMinutes) {
                $this->merge(['loai_gio' => 'cung_ngay']);
                return;
            }

            if ($endMinutes < $startMinutes) {
                $this->merge(['loai_gio' => 'qua_dem']);
                return;
            }

            // ===== 6️⃣ Nếu không rơi vào trường hợp nào khác => lỗi =====
            $validator->errors()->add('gio_hoat_dong', 'Giờ hoạt động không hợp lệ');
        });
    }

    public function messages(): array
    {
        return [
            'gio_bat_dau.required' => 'Giờ hoạt động không hợp lệ, vui lòng nhập đúng định dạng hh:mm',
            'gio_ket_thuc.required' => 'Giờ hoạt động không hợp lệ, vui lòng nhập đúng định dạng hh:mm',
        ];
    }
}
