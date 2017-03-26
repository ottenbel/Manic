<?php

namespace App\Model;

use Webpatser\Uuid\Uuid;

trait Uuids
{
    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
			if (empty($model->{$key}))
			{
				$model->{$model->getKeyName()} = Uuid::generate()->string;
			}
        });
    }
}
