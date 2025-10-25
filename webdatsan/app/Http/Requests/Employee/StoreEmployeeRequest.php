<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return array_merge(
            $this->hoTenRules(),
            $this->gioiTinhRules(),
            $this->ngaySinhRules(),
            $this->soDienThoaiRules(),
        );
    }

    public function hoTenRules(): array
    {
        return ['name' => [new ValidHoTen()]];
    }

    public function gioiTinhRules(): array
    {
        return ['gender' => [new ValidGioiTinh()]];
    }

    public function ngaySinhRules(): array
    {
        return ['dob' => [new ValidNgaySinh()]];
    }

    public function soDienThoaiRules(): array
    {
        return ['phone' => [new ValidSoDienThoai()]];
    }
}
