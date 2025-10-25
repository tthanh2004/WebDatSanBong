<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeAccountRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return array_merge(
            $this->usernameRules(),
            $this->passwordRules(),
        );
    }

    public function usernameRules(): array
    {
        return [ 'username' => [ new ValidUsername() ] ];
    }

    public function passwordRules(): array
    {
        return [ 'password' => [ new ValidPassword() ] ];
    }
}
