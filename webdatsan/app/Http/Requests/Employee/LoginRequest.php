<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge(
            $this->usernameRules(),
            $this->passwordRules(),
        );
    }

    // ================= Username =================
    public function usernameRules(): array
    {
        return [
            'username' => [new ValidLoginUsername()],
        ];
    }

    // ================= Password =================
    public function passwordRules(): array
    {
        return [
            'password' => [new ValidLoginPassword()],
        ];
    }
}
