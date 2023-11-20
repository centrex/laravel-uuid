<?php

declare(strict_types=1);

namespace Centrex\LaravelUuid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasUuid
{
    public static function bootUuidGenerator(): void
    {
        static::creating(function (Model $model) {
            if (Schema::hasColumn($model->getTable(), config('laravel-uuid.default_uuid_column'))) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    /**
     * Scope  by uuid
     *
     * @param  string  uuid of the model.
     */
    public function scopeUuid($query, $uuid, $first = true)
    {
        $match = preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $uuid);

        if ( ! is_string($uuid) || $match !== 1) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        $results = $query->where($this->getTable().'.'.config('laravel-uuid.default_uuid_column'), $uuid);

        return $first ? $results->firstOrFail() : $results;
    }
}
