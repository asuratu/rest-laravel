<?php

namespace Modules\Admin\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    public function transform(Model $model): array
    {
        $array = $model->toArray();

        if (array_key_exists('deleted_at', $array)) {
            unset($array['deleted_at']);
        }

        return $array;
    }
}
