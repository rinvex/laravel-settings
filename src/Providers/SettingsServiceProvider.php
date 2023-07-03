<?php

declare(strict_types=1);

namespace Rinvex\Settings\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Rinvex\Settings\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Settings\Console\Commands\CacheCommand;
use Rinvex\Settings\Console\Commands\ClearCommand;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rinvex\Settings\Console\Commands\MigrateCommand;
use Rinvex\Settings\Console\Commands\PublishCommand;
use Rinvex\Settings\Console\Commands\RollbackCommand;

class SettingsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        CacheCommand::class,
        ClearCommand::class,
        MigrateCommand::class,
        PublishCommand::class,
        RollbackCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.settings');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'rinvex.settings.setting' => Setting::class,
        ]);

        // Register console commands
        $this->commands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Register paths to be published by the publish command.
        $this->publishConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex/settings');
        $this->publishMigrationsFrom(realpath(__DIR__.'/../../database/migrations'), 'rinvex/settings');

        ! $this->app['config']['rinvex.settings.autoload_migrations'] || $this->loadMigrationsFrom(realpath(__DIR__.'/../../database/migrations'));

        $this->app->macro('getCachedSettingsPath', fn () => $this->normalizeCachePath('APP_SETTING_CACHE', 'cache/settings.php'));

        // Map relations
        Relation::morphMap([
            'setting' => config('rinvex.settings.models.setting'),
        ]);

        try {
            // Just check if we have DB connection! This is to avoid
            // exceptions on new projects before configuring database options
            DB::connection()->getPdo();

            if (Schema::hasTable(config('rinvex.settings.tables.settings'))) {
                // Run LoadSettings bootstrap class
                $this->app->bootstrapWith([config('rinvex.settings.bootstrap')]);
            }
        } catch (Exception $e) {
            // Be quiet! Do not do or say anything!!
        }
    }
}
