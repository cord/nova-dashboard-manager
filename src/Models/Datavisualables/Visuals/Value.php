<?php

namespace Marispro\NovaDashboardManager\Models\Datavisualables\Visuals;

use DigitalCreative\NovaDashboard\Filters;
use DigitalCreative\ValueWidget\Widgets\ValueResult;
use DigitalCreative\ValueWidget\Widgets\ValueWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Marispro\NovaDashboardManager\Traits\DynamicMetricsTrait;
use Illuminate\Http\Request;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class Value extends ValueWidget
{
//    use DynamicMetricsTrait;
//    use GlobalFilterable;

    var $baseUriKey = 'value';

    public static $title = 'my Widget';

    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */

    public function metricCalculate(Collection $options, Filters $filters, $visual) {
//        dd($this->meta['metric']);
         return $this->meta['metric']->calculate($options, $filters, $visual);
     }

    public function resolveValue(Collection $options, Filters $filters): ValueResult
    {

        $current = $this->metricCalculate($options, $filters, $this);


        return ValueResult::make()
                          ->currentValue($current)
                          ->previousValue(10);
    }


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return 'my label';
    }
    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->meta('title');
    }

    public function uriKey(): string
    {
        return $this->meta('uriKey');
    }
}
