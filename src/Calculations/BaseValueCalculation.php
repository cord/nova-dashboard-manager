<?php

namespace Marispro\NovaDashboardManager\Calculations;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use \Laravel\Nova\Makeable;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Query\ApplyFilter;

abstract class BaseValueCalculation extends Value
{
    use Calculatable;
}