<?php

namespace Marispro\NovaDashboardManager\Calculations;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use \Laravel\Nova\Makeable;
use Laravel\Nova\Query\ApplyFilter;

abstract class BaseCalculation
{
    use Makeable;


    /**
     * The element's component.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    public $query;

    /**
     * Create a new calculation.
     *
     */
    public function __construct()
    {
        $this->query = $this->newQuery();
    }

    public function query()
    {
        return $this->query;
    }


    abstract public function newQuery();

    public function applyFilter($filters, $filterClass, $options = [])
    {
        $builder = $this->query;

        return tap($this->query, function (Builder $builder) use ($filters, $filterClass, $options) {
            $filters->filters()
                ->each(static function (ApplyFilter $applyFilter) use ($builder, $filterClass, $options) {
                    if (get_class($applyFilter->filter) == $filterClass) {
                        $applyFilter->filter->withMeta($options)->apply(resolve(NovaRequest::class), $builder, $applyFilter->value);
                    }
                });
        });
    }
}