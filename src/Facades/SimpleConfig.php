<?php

namespace LaravelConfigs\Facades;

use Illuminate\Support\Facades\Facade;

class SimpleConfig extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'SimpleConfig';
    }
}
