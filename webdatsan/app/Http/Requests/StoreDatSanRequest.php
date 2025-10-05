<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DatSan;

class StoreDatSanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép tất cả user gửi request
    }

    public function rules(): array
    {
        return [
            'ho_ten'        => 'required|string|max:255',
            'so_dien_thoai' => 'required|regex:/^0[0-9]{9}$/',
            'email'         => 'nullable|email|max:254',
            'ngay_dat'      => 'required|date',
            'gio_bat_dau'   => 'required|date_format:H:i',
            'gio_ket_thuc'  => 'required|date_format:H:i|after:gio_bat_dau',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $san = $this->route('sanId'); // Lấy id sân từ route
            $ngayDat = $this->ngay_dat;
            $start   = $this->gio_bat_dau;
            $end     = $this->gio_ket_thuc;

            // Kiểm tra trùng lịch đặt sân
            $isBooked = DatSan::where('san_bong_id', $san)
                ->where('ngay_dat', $ngayDat)
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('gio_bat_dau', [$start, $end])
                          ->orWhereBetween('gio_ket_thuc', [$start, $end])
                          ->orWhere(function ($q) use ($start, $end) {
                              $q->where('gio_bat_dau', '<', $start)
                                ->where('gio_ket_thuc', '>', $end);
                          });
                })
                ->where('trang_thai', '!=', 'cancelled')
                ->exists();

            if ($isBooked) {
                $validator->errors()->add('gio_bat_dau', 'Khung giờ này đã có người đặt sân.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'ho_ten.required'        => 'Vui lòng nhập họ tên.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.regex'    => 'Số điện thoại phải bắt đầu bằng 0 và có 10 số.',
            'email.email'            => 'Email không đúng định dạng.',
            'ngay_dat.required'      => 'Vui lòng chọn ngày đặt sân.',
            'gio_bat_dau.required'   => 'Vui lòng nhập giờ bắt đầu.',
            'gio_ket_thuc.required'  => 'Vui lòng nhập giờ kết thúc.',
            'gio_ket_thuc.after'     => 'Giờ kết thúc phải sau giờ bắt đầu.',
        ];
    }
}
