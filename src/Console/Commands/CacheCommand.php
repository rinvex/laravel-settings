<?php

declare(strict_types=1);

namespace Rinvex\Settings\Console\Commands;

use Throwable;
use LogicException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Rinvex\Settings\Bootstrap\LoadSettings;
use Symfony\Component\Console\Attribute\AsCommand;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

#[AsCommand(name: 'setting:cache')]
class CacheCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'setting:cache';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'setting:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a cache file for faster settings loading';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new settings cache command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws \LogicException
     */
    public function handle()
    {
        $this->callSilent('setting:clear');

        $settings = $this->getFreshSettings();

        $settingsPath = $this->laravel->getCachedSettingsPath();

        $this->files->put(
            $settingsPath, '<?php return '.var_export($settings, true).';'.PHP_EOL
        );

        try {
            require $settingsPath;
        } catch (Throwable $e) {
            $this->files->delete($settingsPath);

            throw new LogicException('Your settings are not serializable.', 0, $e);
        }

        $this->components->info('Settings cached successfully.');
    }

    /**
     * Boot a fresh copy of the application settings.
     *
     * @return array
     */
    protected function getFreshSettings()
    {
        return LoadSettings::getAppSettings();
    }
}
