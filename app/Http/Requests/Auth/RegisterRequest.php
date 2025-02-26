<?php

namespace App\Http\Requests\Auth;

use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\CustomFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use ApiResponse, CustomFailedValidation;

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
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:filter|max:250|unique:users',
            'password' => 'required|string|min:8|max:50|confirmed'
        ];
    }
}
