<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'email'    => 'required|email|exists:users,email',
            'login' => ['required'],
            'password' => 'required|min:6'
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $login = request('login');

            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                if (!\App\Models\User::where('email', $login)->exists()) {
                    $validator->errors()->add('login', 'Email not registered');
                }
            } else {
                if (!\App\Models\User::where('mobile', $login)->exists()) {
                    $validator->errors()->add('login', 'Mobile number not registered');
                }
            }
        });
    }
    public function messages(): array
    {
        return [
            'email.exists' => 'Email not registered',
        ];
    }
}
