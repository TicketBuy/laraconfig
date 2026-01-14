<?php

namespace Nabcellent\Laraconfig\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Nabcellent\Laraconfig\Registrar\Declaration;
use Nabcellent\Laraconfig\Registrar\SettingRegistrar;

/**
 * @method static Collection|\Nabcellent\Laraconfig\Eloquent\Setting[] getSettings()
 * @method static Declaration name(string $name)
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SettingRegistrar::class;
    }
}
