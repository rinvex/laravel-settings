<?php

declare(strict_types=1);

if (! function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed             $default
     *
     * @return mixed|\Rinvex\Settings\Collections\SettingCollection
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('rinvex.settings');
        }

        if (is_array($key)) {
            return app('rinvex.settings')->set($key);
        }

        return app('rinvex.settings')->get($key, $default);
    }
}
