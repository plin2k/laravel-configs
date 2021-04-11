<?php

namespace LaravelConfigs\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Psr\SimpleCache\InvalidArgumentException;

abstract class BaseConfigService
{
    const CACHE_TTL = 86400;
    const CAST_TYPE = 'string';

    abstract protected static function getEntityModel(): string;

    private static function getEntityName(): string
    {
        return strtolower(basename(static::getEntityModel()));
    }

    protected static function getKeyFieldName(): string
    {
        return 'name';
    }

    protected static function getValueFieldName(): string
    {
        return 'value';
    }

    private static function getCacheKey(string $configName): string
    {
        $entityName = static::getEntityName();

        return "{$entityName}.{$configName}";
    }

    private static function getQuery(): Builder
    {
        return forward_static_call([static::getEntityModel(), 'query']);
    }

    private static function cacheAll(): void
    {
        $keyFieldName = static::getKeyFieldName();
        $valueFieldName = static::getValueFieldName();

        $models = static::getQuery()->get([$keyFieldName, $valueFieldName]);

        foreach ($models as $model) {
            static::cacheSingle($model);
        }
    }

    public static function cacheSingle(Model $model): void
    {
        if (!is_a($model, static::getEntityModel())) {
            Log::error("Wrong model cache attempt. Service: " . static::class . "; Model: " . get_class($model) . "");
        }

        $keyFieldName = static::getKeyFieldName();
        $valueFieldName = static::getValueFieldName();

        $cacheKey = static::getCacheKey($model->{$keyFieldName});

        try {
            Cache::set($cacheKey, $model->{$valueFieldName}, static::CACHE_TTL);
        } catch (InvalidArgumentException $e) {
            Log::error("Cache attempt failed. Service: " . static::class . "; Model: " . get_class($model) . "");
        }
    }

    /**
     * @param string $name
     * @param string|null $default
     * @return mixed
     */
    public static function get(string $name, string $default = null)
    {
        $cacheKey = static::getCacheKey($name);

        if (!Cache::has($cacheKey)) {
            static::cacheAll();
        }

        $value = Cache::get($cacheKey, $default);

        settype($value, static::CAST_TYPE);

        return $value;
    }

    public static function set(string $name, string $value, string $description = "", bool $is_public = false)
    {
        $model = static::getEntityModel();

        return $model::updateOrCreate(['name' => $name], ['value' => $value, 'description' => $description, 'is_public' => $is_public])->value;
    }
}
