<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;


use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Marispro\NovaDashboardManager\Calculations\ActionEventTrendCalculation;
use Marispro\NovaDashboardManager\Calculations\ActionEventValueCalculation;
use Marispro\NovaDashboardManager\Calculations\WidgetTrendCalculation;
use Marispro\NovaDashboardManager\Calculations\WidgetValueCalculation;
use Marispro\NovaDashboardManager\Nova\Filters\ActionEventType;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterFrom;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterTo;
use Illuminate\Http\Request;
use Laravel\Nova\Actions\ActionEvent;
use Marispro\NovaDashboardManager\Nova\Filters\DateRangeDefined;

class actionEvents extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = [
        'Value' => 'Number of Action Events',
        'LineChart' => 'Linechart-Trend of Action Events',
        'BarChart' => 'Barchart-Trend of Action Events'
    ];

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

                $calcuation = ActionEventValueCalculation::make();

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
                $calcuation = ActionEventTrendCalculation::make();

                $dateValue = $filters->getFilterValue(DateRangeDefined::class);

                $result = $this->formatTrendData($dateValue, $calcuation);

                return [
                    'labels' => $result['labels'],
                    'datasets' => [
                        'Action Events' => [
                            'name' => 'Action Events',
                            'data' => $result['values'],
                            'options' => []
                        ]
                    ]
                ];
                break;

        }
    }
}
