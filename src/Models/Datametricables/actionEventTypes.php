<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;


use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Marispro\NovaDashboardManager\Calculations\ActionEventTrendCalculation;
use Marispro\NovaDashboardManager\Calculations\ActionEventTypeTrendCalculation;
use Marispro\NovaDashboardManager\Calculations\ActionEventTypeValueCalculation;
use Marispro\NovaDashboardManager\Nova\Filters\ActionEventType;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\ActionEvent;
use Marispro\NovaDashboardManager\Nova\Filters\DateRangeDefined;

class actionEventTypes extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = [
        'Value' => 'Number of Action Event Types',
        'LineChart' => 'Linechart-Trend of Action Event Types',
        'BarChart' => 'Barchart-Trend of Action Event Types'
    ];

    public static function getResourceModel()
    {
        return \Marispro\NovaDashboardManager\Nova\Datametricables\actionEventTypes::class;
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

                $calcuation = ActionEventTypeValueCalculation::make();


                $calcuationCurrentValue = (clone $calcuation)->applyFilter($filters, DateRangeDefined::class,
                    ['dateColumn' => 'created_at']
                );





                $calcuationPreviousValue = (clone $calcuation)->applyFilter($filters, DateRangeDefined::class,
                    ['dateColumn' => 'created_at', 'previousRange' => true]
                );

                return [
                    'currentValue' => $calcuationCurrentValue->query()->get()->count(),
                    'previousValue' => $calcuationPreviousValue->query()->get()->count()
                ];


                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\LineChart::class :
            case \Marispro\NovaDashboardManager\Models\Datavisualables\BarChart::class :

                // Using Nova Trend calculations
                $calcuation = ActionEventTypeTrendCalculation::make();

                $dateValue = $filters->getFilterValue(DateRangeDefined::class);

                $eventTypes = (new ActionEvent())->newQuery()->select('actionable_type')->distinct()->get()->toArray();


                $dataset = [];
                $labels = null;

                //workaround - could be done in one query

                foreach ($eventTypes as $eventType) {
                    $typeCalcuation = (clone $calcuation);
                    $typeCalcuation->query()->where('actionable_type', '=', $eventType['actionable_type']);

                    $data = $this->formatTrendData($dateValue, $typeCalcuation);

                    $dataset[$eventType['actionable_type']] = [
                        'name' => $eventType['actionable_type'],
                        'data' => $data['values']
                    ];
                    if (!$labels) {
                        $labels = $data['labels'];
                    }

                }

                return [
                    'labels' => $labels,
                    'datasets' => $dataset
                ];

                break;

        }
    }
}
