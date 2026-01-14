<?php

namespace Nabcellent\Laraconfig\Migrator\Pipes;

use Closure;
use Nabcellent\Laraconfig\Migrator\Data;
use Nabcellent\Laraconfig\Registrar\SettingRegistrar;

/**
 * @internal
 */
class LoadDeclarations
{
    /**
     * LoadDeclarations constructor.
     */
    public function __construct(protected SettingRegistrar $registrar) {}

    /**
     * Handles the Settings migration.
     */
    public function handle(Data $data, Closure $next): mixed
    {
        $this->registrar->loadDeclarations();

        // We won't overload the declarations if the data is not empty.
        $data->declarations = $this->registrar->getDeclarations();

        return $next($data);
    }
}
