<?php

namespace App\Http\Requests\ValidationDatSan;

trait ValidNgayDat
{
    public function ngayDatRules(): array
    {
        return [
            'ngay_dat' => ['required', 'date', new ValidateNgayDat()],
        ];
    }

    public function ngayDatMessages(): array
    {
        return [
            'ngay_dat.required' => 'Vui lòng chọn thời gian (Lỗi 1E16).',
        ];
    }
}
