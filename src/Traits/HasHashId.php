<?php

namespace PioneerDynamics\LaravelHashid\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Vinkla\Hashids\Facades\Hashids as LaravelHashids;

trait HasHashId
{
    protected static function getHashIdConnection()
    {
        $connection = self::autoResolveConnectionName();

        return config('hashids.connections.' . $connection)
            ? $connection
            : config('hasids.default');
    }

    private static function autoResolveConnectionName()
    {
        return class_basename(self::class);
    }

    public function initializeHasHashId()
    {
        $this->append('hash_id');

        $this->makeHidden('id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return rescue(
            fn () => self::findOrFail(LaravelHashids::connection(self::getHashIdConnection())->decode($value)[0]),
            fn () => abort(404)
        );
    }

    public function hashId(): Attribute
    {
        return Attribute::make(
            get: fn () => LaravelHashids::connection(self::getHashIdConnection())->encode($this->id)
        );
    }

    public static function findByHashID($hash_id)
    {
        $connection_name = self::getHashIdConnection();

        logger(__CLASS__.'::'.__FUNCTION__.':'.__LINE__.' message ', [
            'connection_name' => $connection_name,
            'config' => config('hashids.connections')
        ] );

        $id = LaravelHashids::connection($connection_name)->decode($hash_id)[0];

        logger(__CLASS__.'::'.__FUNCTION__.':'.__LINE__.' message ', [
            'id' => $id,
        ] );

        return self::withoutGlobalScopes()->findOrFail($id);
    }
}
