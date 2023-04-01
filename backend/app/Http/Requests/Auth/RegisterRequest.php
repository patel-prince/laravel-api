<?php

namespace App\Http\Requests\Auth;

use App\Helper\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class RegisterRequest extends FormRequest {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:32|regex:'.Config::get('global.regex.password')
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => Config::get('global.message.register.firstname.required'),
            'firstname.max' => Config::get('global.message.register.firstname.max'),
            'lastname.required' => Config::get('global.message.register.lastname.required'),
            'lastname.max' => Config::get('global.message.register.lastname.max'),
            'email.required' => Config::get('global.message.register.email.required'),
            'email.email' => Config::get('global.message.register.email.email'),
            'email.max' => Config::get('global.message.register.email.max'),
            'email.unique' => Config::get('global.message.register.email.unique'),
            'phone.required' => Config::get('global.message.register.phone.required'),
            'phone.max' => Config::get('global.message.register.phone.max'),
            'phone.regex' => Config::get('global.message.register.phone.regex'),
            'password.required' => Config::get('global.message.register.password.required'),
            'password.min' => Config::get('global.message.register.password.min'),
            'password.max' => Config::get('global.message.register.password.max'),
            'password.regex' => Config::get('global.message.register.password.regex'),
        ];
    }

    public function failedValidation(Validator $validator)
    {
        (new Helper)->failedValidation($validator);
    }

}
