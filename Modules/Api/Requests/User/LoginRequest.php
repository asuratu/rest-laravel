<?php

namespace Modules\Api\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => [
                'required',
                'regex:' . config('regular.mobile'),
                'exists:users'
            ],
            'password' => 'required|string|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'mobile' => '手机号码'
        ];
    }

    public function messages()
    {
        return [
            'mobile.exists' => '该手机号码还未注册～'
        ];
    }
}
