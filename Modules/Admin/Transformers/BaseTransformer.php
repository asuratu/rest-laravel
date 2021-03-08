<?php

namespace Modules\Admin\Transformers;

use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Model;

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
