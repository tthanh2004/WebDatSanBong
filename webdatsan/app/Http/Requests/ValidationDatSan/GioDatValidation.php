<?php

namespace App\Http\Requests\Validations;

use Illuminate\Contracts\Validation\Rule;
use App\Models\DatSan;
use App\Models\SanBong;

/**
 * Trait gom rule kiểm tra giờ đặt sân
 */
trait ValidateGioDat
{
    public function gioDatRules(): array
    {
        return [
            'gio_bat_dau'  => ['date_format:H:i'],          //1
            'gio_ket_thuc' => ['date_format:H:i',           //2
            'after:gio_bat_dau',                //3
            new ValidGioDat()],         //4
        ];
    }

    public function gioDatMessages(): array
    {
        return [
            'gio_ket_thuc.after' => 'Thời gian kết thúc phải lớn hơn thời gian bắt đầu (Lỗi 1E13).',        //5
        ];
    }
}

class ValidGioDat implements Rule
{
    protected string $errorCode = '';           //6

    public function passes($attribute, $value)
    {
        $request = request();           //7
        $sanId   = $request->route('sanId');        //8
        $san     = SanBong::find($sanId);       //9
        $start   = $request->gio_bat_dau;           //10
        $end     = $request->gio_ket_thuc;          //11
        $ngay    = $request->ngay_dat;      //12

        if ($san && ($start < $san->start_time || $end > $san->end_time)) {         //13
            $this->errorCode = '1E14';
            return false;
        }

        $isBooked = DatSan::where('san_bong_id', $sanId)            //14
            ->where('ngay_dat', $ngay)
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

        if ($isBooked) {            //15
            $this->errorCode = '1E15';
            return false;
        }

        return true;            //16
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E14' => 'Giờ thuê phải nằm trong giờ hoạt động của sân (Lỗi 1E14).',              //17
            '1E15' => 'Sân đã có người đặt khung giờ này (Lỗi 1E15).',                  //18
        };
    }
}
