<?php

declare(strict_types=1);

namespace Rinvex\Settings\Bootstrap;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Rinvex\Settings\Collections\SettingCollection;
use Illuminate\Contracts\Database\Eloquent\Builder;

class LoadSettings
{
    /**
     * Bootstrap the given application.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @throws Exception
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        // We will spin through all the setting groups and load them into the repository.
        // This will make all the options available to the developer for use across the whole app.
        $app->instance('rinvex.settings', $settings = new SettingCollection([]));

        // First we will see if we have a cache settings file. If we do, we'll load
        // the setting items from that file so that it is very quick. Otherwise
        // we will need to spin through every setting and load them all.
        $settings->set($appSettings = (file_exists($cached = $app->getCachedSettingsPath()) ? require $cached : self::getAppSettings()->toArray()));

        // Override config options dynamically on the fly
        collect($appSettings)->filter(fn($setting) => $setting['override_config'] === true)->each(fn($setting) => config()->set($setting['key'], $setting['value']));
    }

    /**
     * Get app settings.
     *
     * @return array
     */
    public static function getAppSettings()
    {
        return app('rinvex.settings.setting')->get()->keyBy('key')->toBase();
    }
}
