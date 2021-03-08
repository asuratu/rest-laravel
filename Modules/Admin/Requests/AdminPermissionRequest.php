<?php

namespace Modules\Admin\Requests;

use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Modules\Admin\Entities\AdminPermission;
use Modules\Admin\Rules\AdminPermissionHttpPath;

class AdminPermissionRequest extends FormRequest
{
    public function rules(): array
    {
        $id = (int) optional($this->route('admin_permissions'))->id;

        $rules = [
            'name' => 'required|unique:admin_permissions,name,' . $id,
            'slug' => 'required|unique:admin_permissions,slug,' . $id,
            'http_method' => 'nullable|array',
            'http_method.*' => Rule::in(AdminPermission::$httpMethods),
            'http_path' => [
                'nullable',
                new AdminPermissionHttpPath(),
            ],
        ];

        if ($this->isMethod('put')) {
            $rules = Arr::only($rules, $this->keys());
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name' => '名称',
            'slug' => '标识',
            'http_method' => '请求方法',
            'http_method.*' => '请求方法',
            'http_path' => '请求地址',
        ];
    }
}
