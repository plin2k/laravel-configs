<?php

namespace LaravelConfigs\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopePublic
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }
}
