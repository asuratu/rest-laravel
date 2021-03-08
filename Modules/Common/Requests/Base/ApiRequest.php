<?php

namespace Modules\Common\Requests\Base;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
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

    public function validateData($data, $rules, $message = [], $attributes = [])
    {
        $validate = Validator::make($data, $rules, $message, $attributes);

        if ($validate->fails()) {
            $error = $validate->errors()->all();
            throw new HttpResponseException(response()->json(['message' => $error[0], 'status_code' => 422], 422));
        }
    }
}
