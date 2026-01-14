<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Nabcellent\Laraconfig\Facades\Setting;
use Nabcellent\Laraconfig\LaraconfigServiceProvider;

trait RegistersPackage
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaraconfigServiceProvider::class,
        ];
    }

    /**
     * Override application aliases.
     *
     * @param  Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Setting' => Setting::class,
        ];
    }
}
