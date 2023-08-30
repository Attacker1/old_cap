<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class VuexFilter extends Builder
{
    public function vuexFilter()
    {

        foreach (request()->all() as $name => $value) {

            if (method_exists($this, $name)) {
                call_user_func_array([$this, $name], array_filter([$value]));
            }
        }
        return $this;
    }
}
