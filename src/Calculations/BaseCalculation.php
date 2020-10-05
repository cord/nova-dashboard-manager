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

    public function applyFilter($query, $filter) {

//        static function (ApplyFilter $applyFilter) use ($query) {
//            $applyFilter->filter->apply(resolve(NovaRequest::class), $builder, $applyFilter->value);
//        })

        $applyFilter = (new $filter);

        $query = $applyFilter->apply(resolve(NovaRequest::class), $query, $applyFilter->value);
        dd($query);

        return $query;
    }
}