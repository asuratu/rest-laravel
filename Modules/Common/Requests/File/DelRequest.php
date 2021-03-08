<?php

namespace Modules\Common\Requests\File;

use Illuminate\Validation\Rule;
use Modules\Common\Requests\Base\ApiRequest;

class DelRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'id' => ['required', 'string', Rule::exists('tz_upload_file', 'md5')],
        ];
    }

    public function messages()
    {
        return [
            'id.exists' => '文件不存在',
        ];
    }
}
