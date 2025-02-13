<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;


trait HasPublicID
{
    /**
     * Boot the trait and add creating event listener to set public_id.
     */
    protected static function bootHasPublicID()
    {
        static::creating(function ($model) {
            $model->public_id = generateNanoId(10);
        });
    }
}