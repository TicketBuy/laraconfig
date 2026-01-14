<?php

namespace Tests\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Tests\BaseTestCase;

class PublishCommandTest extends BaseTestCase
{
    protected Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = new Filesystem;
    }

    public function test_adds_sample_file_into_settings(): void
    {
        $this->artisan('settings:publish')
            ->expectsOutput("Manifest published. Check it at: {$this->app->basePath('settings/users.php')}")
            ->assertExitCode(0);

        static::assertFileExists($this->app->basePath('settings/users.php'));
    }

    public function test_confirms_manifest_replace(): void
    {
        $this->filesystem->ensureDirectoryExists($this->app->basePath('settings'));
        $this->filesystem->put($this->app->basePath('settings/users.php'), '');

        $this->artisan('settings:publish')
            ->expectsConfirmation('A manifest file already exists. Overwrite?')
            ->assertExitCode(0);

        static::assertFileExists($this->app->basePath('settings/users.php'));
        static::assertStringEqualsFile(
            $this->app->basePath('settings/users.php'),
            ''
        );
    }

    public function test_replaces_manifest_once_confirmed(): void
    {
        $this->filesystem->ensureDirectoryExists($this->app->basePath('settings'));
        $this->filesystem->put($this->app->basePath('settings/users.php'), '');

        $this->artisan('settings:publish')
            ->expectsConfirmation('A manifest file already exists. Overwrite?', 'yes')
            ->expectsOutput("Manifest published. Check it at: {$this->app->basePath('settings/users.php')}")
            ->assertExitCode(0);

        static::assertFileExists($this->app->basePath('settings/users.php'));

        sleep(10);

        static::assertFileEquals(
            __DIR__.'/../../../stubs/users.php',
            $this->app->basePath('settings/users.php')
        );
    }

    protected function tearDown(): void
    {
        $this->filesystem->deleteDirectory($this->app->basePath('settings'));

        parent::tearDown();
    }
}
