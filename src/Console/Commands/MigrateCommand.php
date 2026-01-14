<?php

namespace Nabcellent\Laraconfig\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Nabcellent\Laraconfig\Migrator\Data;
use Nabcellent\Laraconfig\Migrator\Migrator;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @internal
 */
class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:migrate
                            {--refresh : Wipes clean the settings table and metadata table.}
                            {--flush-cache : Flushes the cache used by Laraconfig, if enabled.}
                            {--force : Skips confirmation prompt on production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes and updates the settings from the database';

    /**
     * MigrateCommand constructor.
     */
    public function __construct(protected Migrator $migrator, protected Data $data)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // We will use the Input interface to use the same instance.
        $this->getLaravel()->instance(InputInterface::class, $this->input);
        $this->getLaravel()->instance(OutputStyle::class, $this->output);

        try {
            $this->migrator->send($this->data)->thenReturn();
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return 1;
        }

        return 0;
    }
}
