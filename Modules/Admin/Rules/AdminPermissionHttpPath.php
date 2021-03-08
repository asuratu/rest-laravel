<?php

namespace Modules\Admin\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;
use Modules\Admin\Entities\AdminPermission;

class AdminPermissionHttpPath implements Rule
{
    protected $errorMethods;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $path = array_filter(explode("\n", $value));
        foreach ($path as $i) {
            if (Str::contains($i, ':')) {
                [$methods, $path] = explode(':', $i);
                if ($methods) {
                    $methods = explode(',', $methods);
                    $errorMethods = array_diff($methods, AdminPermission::$httpMethods);
                    if (!empty($errorMethods)) {
                        $this->errorMethods = implode(',', $errorMethods);

                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "HTTP 方法 [ {$this->errorMethods} ] 无效";
    }
}
