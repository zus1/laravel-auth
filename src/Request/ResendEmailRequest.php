<?php

namespace Zus1\LaravelAuth\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Zus1\LaravelAuth\Constant\TokenType;

class ResendEmailRequest extends FormRequest
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
            'email' => 'required|email|exists:users',
            'type' => [
                'required',
                Rule::in(TokenType::USER_RESET_PASSWORD, TokenType::USER_VERIFICATION),
            ]
        ];
    }
}
