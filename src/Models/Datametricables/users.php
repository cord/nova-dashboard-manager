<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;


use DigitalCreative\NovaDashboard\Examples\Filters\Category;
use DigitalCreative\NovaDashboard\Examples\Filters\Date;
use DigitalCreative\NovaDashboard\Examples\Filters\Quantity;
use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marispro\NovaDashboardManager\Calculations\UserTrendCalculation;
use Marispro\NovaDashboardManager\Calculations\UserValueCalculation;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterFrom;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterTo;
use App\User;
use Illuminate\Http\Request;
use Marispro\NovaDashboardManager\Nova\Filters\DateRangeDefined;

class users extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = [
        'Value' => 'Number of Users',
        'LineChart' => 'Linechart-Trend of Users',
        'BarChart' => 'Barchart-Trend of Users'

//        , 'Partition' // Bug in Nova https://github.com/laravel/nova-issues/issues/2681

    ];

    public static function getResourceModel()
    {
        return \Marispro\NovaDashboardManager\Nova\Datametricables\users::class;
    }

    public function getOnlyVerifiedEmailAttribute()
    {
        return $this->extra_attributes->only_verified_email;
    }


    public function setOnlyVerifiedEmailAttribute($value)
    {
        $this->extra_attributes->only_verified_email = $value;
    }


    public function calculate(Collection $options, Filters $filters)
    {


        // same
//        dd($visual->meta['metric']->visualable_type);
//        dd($this->visualable_type);


        switch ($this->visualable_type) {
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Value::class :

                $calcuation = UserValueCalculation::make();

                if ($this->only_verified_email) {
                    $calcuation = $calcuation->verified();
                } else {
                    $calcuation = $calcuation;
                }

                // option 1
                // get filter values and calculate result
                // $dateValue = $filters->getFilterValue(DateRangeDefined::class);

                // option 2
                // apply filter with options
                // $calcuation->applyFilter($filters, DateRangeDefined::class,
                //     ['dateColumn' => 'created_at']
                // );


                $calcuationCurrentValue = (clone $calcuation)->applyFilter($filters, DateRangeDefined::class,
                    ['dateColumn' => 'created_at']
                );

                $calcuationPreviousValue = (clone $calcuation)->applyFilter($filters, DateRangeDefined::class,
                    ['dateColumn' => 'created_at', 'previousRange' => true]
                );

                // alternativ approach: use Nova Value calculations
                // $calcuation->count($calcuationCurrentValue->query());

                return [
                    'currentValue' => $calcuationCurrentValue->query()->get()->count(),
                    'previousValue' => $calcuationPreviousValue->query()->get()->count()
                ];
                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\LineChart::class :
            case \Marispro\NovaDashboardManager\Models\Datavisualables\BarChart::class :

                // Using Nova Trend calculations
                $calcuation = UserTrendCalculation::make();
                $request = resolve(NovaRequest::class);

                $dateValue = $filters->getFilterValue(DateRangeDefined::class);

                switch ($dateValue) {
                    case 'ALL':
                    case '365':
                    case 'TODAY':
                    case 'QTD':
                    case 'YTD':

                        $request->range = 12;
                        $result = $calcuation->countByMonths($request, $calcuation->query(), 'created_at');
                        $labels = array_keys($result->trend);
                        break;
                    case '30':
                    case '60':
                    case 'MTD':
                        $request->range = $dateValue;
                        if ($dateValue == 'MTD') {
                            $request->range = 30;
                            // todo
                            $result = $calcuation->countByDays($request, $calcuation->query(), 'created_at');
                        } else {

                            $result = $calcuation->countByDays($request, $calcuation->query(), 'created_at');
                        }
                        $labels_raw = array_keys($result->trend);
                        $first = reset($labels_raw);
                        $last = end($labels_raw);
                        $labels = range(0, $request->range);
                        $labels[0] = $first;
                        $labels[sizeof($labels) - 1] = $last;
                        break;
                    default:
                        $request->range = 12;
                        $result = $calcuation->countByMonths($request, $calcuation->query(), 'created_at');
                        $labels = array_keys($result->trend);
                        break;
                }

                $values = array_values($result->trend);

                return [
                    'labels' => $labels,
                    'datasets' => [
                        'Users' => $values,
                    ]
                ];


                break;

        }
    }

}
