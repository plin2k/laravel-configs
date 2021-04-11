<?php

namespace LaravelConfigs\Services;

use LaravelConfigs\Models\Config;
use LaravelConfigs\Services\BaseConfigService;

class SimpleConfig extends BaseConfigService
{
    protected static function getEntityModel(): string
    {
        return Config::class;
    }
}
