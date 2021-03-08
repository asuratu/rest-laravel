<?php

namespace Modules\Admin\Requests;

/**
 * @property mixed username
 * @property mixed password
 */
class AuthorizationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => [
                'required',
                'string',
                'exists:admin_users'
            ],
            'password' => 'required|string|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'username' => '账户名'
        ];
    }

    public function messages()
    {
        return [
            'username.exists' => '该账户名还未注册～'
        ];
    }
}
