<?php

namespace App\Http\Requests\ValidationDatSan;

trait ValidateHoTen
{
    public function hoTenRules(): array
    {
        return [
            'ho_ten' => new ValidHoTen(),
        ];
    }
}
