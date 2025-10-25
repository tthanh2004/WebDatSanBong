<?php

namespace App\Http\Requests\ValidationDatSan;

trait ValidateLoaiSan
{
    public function loaiSanRules(): array
    {
        return [
            'loai_san_id' => [new ValidLoaiSan()],
        ];
    }
}
