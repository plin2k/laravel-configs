<?php

namespace LaravelConfigs\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelConfigs\Traits\ScopePublic;

class Config extends Model
{
    use ScopePublic;

    public $timestamps = false;

    protected $fillable = ['name', 'value', 'description', 'is_public', 'type'];
}
