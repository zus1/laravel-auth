<?php

namespace Zus1\LaravelAuth\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Zus1\LaravelAuth\Constant\RouteName;

class ResetPasswordRequest extends BaseRequest
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
        if($this->route()->action['as'] === RouteName::RESET_PASSWORD_SEND) {
            return [
                'email' => $this->getEmailRules(),
            ];
        }
        if($this->route()->action['as'] === RouteName::RESET_PASSWORD) {
            return [
                'password' => [
                    'required',
                    Password::min(8)
                        ->letters()
                        ->numbers()
                        ->symbols()
                        ->mixedCase()
                        ->uncompromised(),
                ],
                'confirm_password' => 'same:password'
            ];
        }

        return [];
    }
}
