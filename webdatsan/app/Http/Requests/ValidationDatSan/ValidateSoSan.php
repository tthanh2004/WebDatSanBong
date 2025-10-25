<?php

namespace App\Http\Requests\ValidationDatSan;

trait ValidateSoSan
{
    public function soSanRules(): array
    {
        return [
            'so_san' => [new ValidSoSan()],
        ];
    }
}
