<?php

namespace Nabcellent\Laraconfig\Migrator\Pipes;

use Closure;
use Illuminate\Console\OutputStyle;
use Illuminate\Database\Eloquent\Collection;
use Nabcellent\Laraconfig\Eloquent\Metadata;
use Nabcellent\Laraconfig\Migrator\Data;

/**
 * @internal
 */
class RemoveOldMetadata
{
    /**
     * RemoveOldMetadata constructor.
     */
    public function __construct(protected OutputStyle $output) {}

    /**
     * Handles the Settings migration.
     */
    public function handle(Data $data, Closure $next): mixed
    {
        $count = 0;
        $affected = 0;

        // Find the metadata not present in the manifest.
        foreach ($this->toDelete($data) as $metadata) {
            $affected += $metadata->settings()->delete();
            $metadata->delete();
            $count++;

            $data->invalidateCache = true;
        }

        $this->output->info("Deleted $count metadata settings, with $affected settings deleted.");

        return $next($data);
    }

    /**
     * Returns a collection of Metadata not present in the manifest.
     *
     *
     * @return Collection|Metadata[]
     */
    protected function toDelete(Data $data): Collection
    {
        return $data->metadata->reject(static function (Metadata $metadata) use ($data): bool {
            return $data->declarations->has($metadata->name);
        });
    }
}
