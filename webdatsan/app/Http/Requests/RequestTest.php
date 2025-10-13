<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\DatSan;
use App\Models\SanBong;

class StoreGioDatSanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gio_bat_dau'  => 'required|date_format:H:i',   //1
            'gio_ket_thuc' => 'required|date_format:H:i|after:gio_bat_dau', //2

            'ngay_dat' => 'required|date|after_or_equal:today', //3
        ];
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $sanId   = $this->route('sanId');   //4
            $san     = SanBong::find($sanId);   //5
            $ngayDat = $this->ngay_dat;  //6
            $start   = $this->gio_bat_dau;      //7
            $end     = $this->gio_ket_thuc; //8

            if ($san) { // 9
                if ($start < $san->start_time || $end > $san->end_time) {   //10
                    $validator->errors()->add('gio_bat_dau', 'Giờ thuê phải nằm trong giờ hoạt động của sân (Lỗi 1E14).');
                }
            }

            $isBooked = DatSan::where('san_bong_id', $sanId)    //11
                ->where('ngay_dat', $ngayDat)   //12
                ->where(function ($query) use ($start, $end) {  //13
                    $query->whereBetween('gio_bat_dau', [$start, $end])     //14
                          ->orWhereBetween('gio_ket_thuc', [$start, $end])  //15
                          ->orWhere(function ($q) use ($start, $end) {  //16
                              $q->where('gio_bat_dau', '<', $start)     //17
                                ->where('gio_ket_thuc', '>', $end); //18
                          });
                })
                ->where('trang_thai', '!=', 'cancelled')    //19
                ->exists(); //20

            if ($isBooked) {    //21
                $validator->errors()->add('gio_bat_dau', 'Sân đã có người đặt khung giờ này (Lỗi 1E15).');
            }
        }
        );
    }
    public function messages(): array
    {
     return [
            'gio_bat_dau.required'  => 'Vui lòng nhập giờ bắt đầu (Lỗi 1E13).', //22
            'gio_ket_thuc.required' => 'Vui lòng nhập giờ kết thúc (Lỗi 1E13).',        //23
            'gio_ket_thuc.after'    => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu (Lỗi 1E13).', //24

            'ngay_dat.required'       => 'Vui lòng chọn thời gian (Lỗi 1E16).',     //25
            'ngay_dat.after_or_equal' => 'Thời gian đặt sân không hợp lệ (Lỗi 1E17).',  //26
        ];
    }
}