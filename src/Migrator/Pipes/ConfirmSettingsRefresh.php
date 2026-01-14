<?php

namespace Nabcellent\Laraconfig\Migrator\Pipes;

use Closure;
use Illuminate\Console\OutputStyle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection;
use Nabcellent\Laraconfig\Eloquent\Metadata;
use Nabcellent\Laraconfig\Eloquent\Setting;
use Nabcellent\Laraconfig\Migrator\Data;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @internal
 */
class ConfirmSettingsRefresh
{
    /**
     * ConfirmSettingsToDelete constructor.
     */
    public function __construct(
        protected Application $app,
        protected OutputStyle $output,
        protected InputInterface $input
    ) {}

    /**
     * Handles the Settings migration.
     */
    public function handle(Data $data, Closure $next): mixed
    {
        if ($this->input->getOption('refresh')) {
            if ($this->rejectedRefreshOnProduction()) {
                throw new RuntimeException('Settings refresh has been rejected by the user.');
            }

            // Truncate both tables.
            Setting::query()->truncate();
            Metadata::query()->truncate();

            // Reset the metadata collection since there is nothing left.
            $data->metadata = new Collection;
        }

        return $next($data);
    }

    /**
     * Returns if there the settings data will be refreshed and the developer has rejected that.
     */
    protected function rejectedRefreshOnProduction(): bool
    {
        return $this->shouldPrompt()
            && ! $this->output->confirm('ALL settings will be deleted completely. Proceed?');
    }

    /**
     * Check if the developer should be prompted for refreshing the settings tables.
     */
    protected function shouldPrompt(): bool
    {
        return Metadata::query()->exists()
            && $this->app->environment('production')
            && ! $this->input->getOption('force');
    }
}
