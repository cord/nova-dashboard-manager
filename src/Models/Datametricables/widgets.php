<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;

use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Marispro\NovaDashboardManager\Calculations\WidgetTrendCalculation;
use Marispro\NovaDashboardManager\Calculations\WidgetValueCalculation;
use Marispro\NovaDashboardManager\Models\Dashboard;
use Illuminate\Http\Request;
use Marispro\NovaDashboardManager\Nova\Filters\DateRangeDefined;

class widgets extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = [
        'Value' => 'Number of Widgets',
        'LineChart' => 'Linechart-Trend of Widgets',
        'BarChart' => 'Barchart-Trend of Widgets'
    ];

    public static function getResourceModel()
    {
        return \Marispro\NovaDashboardManager\Nova\Datametricables\widgets::class;
    }


    public function calculate(Collection $options, Filters $filters)
    {

        switch ($this->visualable_type) {
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Value::class :

                $calcuation = WidgetValueCalculation::make();

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
                $calcuation = WidgetTrendCalculation::make();

                $dateValue = $filters->getFilterValue(DateRangeDefined::class);

                $result = $this->formatTrendData($dateValue, $calcuation);

                return [
                    'labels' => $result['labels'],
                    'datasets' => [
                        'Widgets' => [
                            'name' => 'Widgets',
                            'data' => $result['values'],
                            'options' => []
                        ]
                    ]
                ];

                break;

        }
    }

}
