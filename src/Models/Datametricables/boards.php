<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;
use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Marispro\NovaDashboardManager\Calculations\BoardCalculation;
use Marispro\NovaDashboardManager\Calculations\UserCalculation;
use Marispro\NovaDashboardManager\Models\Dashboard;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterFrom;
use Marispro\NovaDashboardManager\Nova\Filters\DateFilterTo;
use Illuminate\Http\Request;

class boards extends BaseDatametricable
{
    /*
     * configure supported visualisationTypes
     * methode 'calculate' must return a valid calculation
     */

    var $visualisationTypes = ['Value', 'Trend'];

    public static function getResourceModel()
    {
        return \Marispro\NovaDashboardManager\Nova\Datametricables\boards::class;
    }

    public function getBoardsMetricOptionAttribute()
    {
        return $this->extra_attributes->boards_metric_option;
    }


    public function setBoardsMetricOptionAttribute($value)
    {
        $this->extra_attributes->boards_metric_option = $value;
    }


    public function calculate(Collection $options, Filters $filters)
    {

        switch ($this->visualable_type) {
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Value::class :

                $calcuation = BoardCalculation::make();

                $query = $calcuation->query();

                return [
                    'currentValue' => $query->get()->count(),
                    'previousValue' => $query->get()->count()
                ];


                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\Trend::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Trend
                 */
                $filteredModel = $visual->globalFiltered((new Databoard)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                ]);
                return $visual->countByDays($request, $filteredModel)->showLatestValue();

                break;

        }
    }

}
