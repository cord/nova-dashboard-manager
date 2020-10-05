<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;


use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Marispro\NovaDashboardManager\Nova\Filters\ActionEventType;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterFrom;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterTo;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\ActionEvent;

class actionEvents extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = ['Value', 'Trend', 'Partition'];

    public static function getResourceModel()
    {
        return \Marispro\NovaDashboardManager\Nova\Datametricables\actionEvents::class;
    }

    public function getActionEventsMetricOptionAttribute()
    {
        return $this->extra_attributes->action_events_metric;
    }

    public function setActionEventsMetricOptionAttribute($value)
    {
        $this->extra_attributes->action_events_metric = $value;
    }

    public function calculate(Collection $options, Filters $filters)
    {
        switch ($this->visualable_type) {
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Value::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Value
                 */
                $request->range = 365 * 100; // otherwise null?

                $filteredModel = $visual->globalFiltered((new ActionEvent)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                    ActionEventType::class,
                ]);

                // use internal methods
                return $visual->count($request, $filteredModel)->suffix('Action Events');

                // calculation
                return $visual
                    ->result($filteredModel->count())
                    ->previous((new Databoard)->count() / 2, 'All')
                    ->prefix('Boards ')
                    ->suffix('for fun')->withoutSuffixInflection();

                break;
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Trend::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Trend
                 */
                $filteredModel = $visual->globalFiltered((new ActionEvent)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                    ActionEventType::class,
                ]);
                return $visual->countByDays($request, $filteredModel)->showLatestValue();

                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\Partition::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Partition
                 */
                $filteredModel = $visual->globalFiltered((new ActionEvent)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                    ActionEventType::class,
                ]);
                return $visual->count($request, $filteredModel, 'actionable_type')
                    ->label(function ($value) {
                        switch ($value) {
                            case null:
                                return 'None';
                            default:
                                return ucfirst(($value));
                        }
                    });

                break;

        }
    }
}
