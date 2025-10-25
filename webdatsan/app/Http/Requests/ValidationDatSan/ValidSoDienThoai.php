<?php

namespace App\Http\Requests\ValidationDatSan;


use Illuminate\Contracts\Validation\Rule;

trait ValidSoDienThoai
{
    public function soDienThoaiRules(): array
    {
        return [
            'so_dien_thoai' => [
                'required',
                new ValidateSoDienThoai(),
            ],
        ];
    }

    public function soDienThoaiMessages(): array
    {
        return [
            'so_dien_thoai.required' => 'Số điện thoại không được để trống (Lỗi 1E4).',
        ];
    }
}
