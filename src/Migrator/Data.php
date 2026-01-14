<?php

namespace Nabcellent\Laraconfig\Migrator;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Nabcellent\Laraconfig\Eloquent\Metadata;
use Nabcellent\Laraconfig\Registrar\Declaration;

/**
 * @internal
 */
class Data
{
    /**
     * Database Metadata.
     *
     * @var EloquentCollection|Metadata[]
     */
    public EloquentCollection|array $metadata;

    /**
     * Declarations.
     *
     * @var Collection|Declaration[]
     */
    public Collection|array $declarations;

    /**
     * Models to check for bags.
     *
     * @var Collection|Model[]
     */
    public Collection $models;

    /**
     * If the cache should be invalidated on settings changes.
     */
    public bool $invalidateCache = false;

    /**
     * Invalidate the cache through the models instead of the settings.
     */
    public bool $useModels = false;

    /**
     * Data constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->models = new Collection;
    }
}
