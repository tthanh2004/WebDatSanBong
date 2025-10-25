<?php

namespace App\Http\Requests\ValidationDatSan;

use Illuminate\Contracts\Validation\Rule;
use App\Models\DatSan;

class ValidSoSan implements Rule
{
    protected string $errorCode = '';

    public function passes($attribute, $value)
    {
        $request = request();
        $sanId   = $request->route('sanId');
        $ngay    = $request->ngay_dat;
        $start   = $request->gio_bat_dau;
        $end     = $request->gio_ket_thuc;

        // FIX 1: trim để '   ' coi như rỗng
        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '' || $value === null) {
            $this->errorCode = '1E18';
            return false;
        }

        $isBooked = DatSan::where('san_bong_id', $sanId)
            ->where('so_san', $value)
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

        if ($isBooked) {
            $this->errorCode = '1E15';
            return false;
        }

        return true;
    }

    public function message()
    {
        return match ($this->errorCode) {
            '1E18' => 'Vui lòng chọn số sân (Lỗi 1E18).',
            '1E15' => 'Sân đã có người đặt khung giờ này (Lỗi 1E15).',
        };
    }
}
