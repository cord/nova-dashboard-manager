<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;


use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Marispro\NovaDashboardManager\Models\Datawidget;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterFrom;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterTo;
use Illuminate\Http\Request;

class widgets extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = ['Value', 'Partition'];

    public static function getResourceModel()
    {
        return \Marispro\NovaDashboardManager\Nova\Datametricables\widgets::class;
    }

    public function getWidgetsMetricOptionAttribute()
    {
        return $this->extra_attributes->widget_metric_option;
    }

    public function setWidgetsMetricOptionAttribute($value)
    {
        $this->extra_attributes->widget_metric_option = $value;
    }

    public function calculate(Collection $options, Filters $filters, $visual)
    {

        return 23;


        switch ($this->visualable_type) {
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Value::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Value
                 */
                $request->range = 365 * 100; // otherwise null?

                $filteredModel = $visual->globalFiltered((new Datawidget)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                ]);

                // use internal methods
                return $visual->count($request, $filteredModel)->suffix('Widgets');

                // calculation
                return $visual
                    ->result($filteredModel->count())
                    ->previous((new Databoard)->count() / 2, 'All')
                    ->prefix('Boards ')
                    ->suffix('for fun')->withoutSuffixInflection();

                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\Partition::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Partition
                 */
                $filteredModel = $visual->globalFiltered((new Datawidget)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                ]);
                return $visual->count($request, $filteredModel, 'metricable_type')
                    ->label(function ($value) {
                        switch ($value) {
                            case null:
                                return 'None';
                            default:
                                return ucfirst(class_basename($value));
                        }
                    });

                break;

        }
    }
}
