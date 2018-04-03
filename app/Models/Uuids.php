<?php

namespace App\Models;

use Illuminate\Support\Str;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function bootUuidsTrait()
    {
        static::creating(function ($model) {
			if (empty($model->{$model->getKeyName()}))
			{
				$model->{$model->getKeyName()} = (string) Str::orderedUuid();
			}
        });
    }
}
