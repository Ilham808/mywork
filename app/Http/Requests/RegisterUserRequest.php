<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Field name wajib diisi',
            'email.required' => 'Field email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Field password wajib diisi',
            'password.confirmed' => 'password tidak sama',
            'password.min' => 'Password minimal 8 karakter'
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 400));
    }
}
