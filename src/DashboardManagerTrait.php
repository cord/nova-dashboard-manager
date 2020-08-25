<?php

namespace Marispro\NovaDashboard;

use Illuminate\Support\Str;
use Laravel\Nova\AuthorizedToSee;
use Laravel\Nova\Makeable;
use Laravel\Nova\Metable;
use Laravel\Nova\ProxiesCanSeeToGate;

trait DashboardManagerTrait
{
    use Makeable;
    use Metable;
    use ProxiesCanSeeToGate;
    use AuthorizedToSee;

    protected static $dashboard;

    public static function humanize(string $value): string
    {
        return Str::title(Str::snake($value, ' '));
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return static::humanize(class_basename(static::class));
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey(): string
    {
        return Str::kebab(class_basename(static::class));
    }

    public static function title(): string
    {
        return "Test title";
    }

    /**
     * @param string|null $key
     * @param null $default
     *
     * @return array|mixed|null
     */
    public function meta(string $key = null, $default = null)
    {

        if ($key !== null) {

            if (array_key_exists($key, $this->meta)) {

                return value($this->meta[ $key ]);

            }

            return $default;

        }

        /**
         * If no key is provided get all meta
         */
        return array_map(function ($item) {
            return value($item);
        }, $this->meta);

    }

}
