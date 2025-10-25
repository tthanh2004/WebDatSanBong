<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ValidationDatSan\ValidateHoTen;
use App\Http\Requests\ValidationDatSan\ValidateSoDienThoai;
use App\Http\Requests\ValidationDatSan\ValidateEmail;
use App\Http\Requests\ValidationDatSan\ValidateGioDat;
use App\App\Http\Requests\ValidationDatSan\ValidateNgayDat;
use App\Http\Requests\ValidationDatSan\ValidateSoSan;

class StoreDatSanRequest extends FormRequest
{
    use ValidateHoTen, ValidateSoDienThoai, ValidateEmail, ValidateGioDat, ValidateNgayDat, ValidateSoSan;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            $this->hoTenRules(),
            $this->soDienThoaiRules(),
            $this->emailRules(),
            $this->gioDatRules(),
            $this->ngayDatRules(),
            $this->soSanRules()
        );
    }
}
