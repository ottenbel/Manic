<?php

namespace App\Models;

use Webpatser\Uuid\Uuid;

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
				$model->{$model->getKeyName()} = Uuid::generate()->string;
			}
        });
    }
}
