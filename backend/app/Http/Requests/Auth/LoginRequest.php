<?php

namespace App\Http\Requests\Auth;

use App\Helper\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Config;

class LoginRequest extends FormRequest {

    public function __construct(array $request = [])
    {
        parent::__construct($request);
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:32|regex:'.Config::get('global.regex.password')
        ];
    }

    public function messages()
    {
        return [
            'email.required' => Config::get('global.message.login.email.required'),
            'email.email' => Config::get('global.message.login.email.email'),
            'email.max' => Config::get('global.message.login.email.max'),
            'password.required' => Config::get('global.message.login.password.required'),
            'password.min' => Config::get('global.message.login.password.min'),
            'password.max' => Config::get('global.message.login.password.max'),
            'password.regex' => Config::get('global.message.login.password.regex'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], 422)
        );
    }

}
