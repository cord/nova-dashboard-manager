<?php


namespace Marispro\NovaDashboardManager\Models\Datametricables;

use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Marispro\NovaDashboardManager\Models\Dashboard;
use Marispro\NovaDashboardManager\Models\Datavisualables\Partition;
use Marispro\NovaDashboardManager\Models\Datavisualables\Trend;
use Marispro\NovaDashboardManager\Models\Datavisualables\Value;
use Marispro\NovaDashboardManager\Models\Datawidget;
use Marispro\NovaDashboardManager\Nova\Filters\DateRangeDefined;
use Marispro\NovaDashboardManager\Traits\HasSchemalessAttributesTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Metrics\TrendResult;
use Illuminate\Http\Request;


class BaseDatametricable extends Model
{
    use HasSchemalessAttributesTrait;

    public $timestamps = true;

    // supported visuals
    var $visualisationTypes = [];

    public $casts = [
        'extra_attributes' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(Str::singular(config('nova-dashboard-manager.tables.metrics')) . '_standard');
    }

    /**
     * @return string[]
     */
    public function getVisualisationTypes(): array
    {
        return $this->visualisationTypes;
    }


    public function datawidgets()
    {
        return $this->morphMany(Datawidget::class, 'metricable');
    }

    public function visualable()
    {
        return $this->morphTo();
    }


    public function calculate(Collection $options, Filters $filters)
    {

        switch ($this->visualable_type) {
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Value::class :

                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\Trend::class :

                /**
                 * @var $visual \Laravel\Nova\Metrics\Trend
                 */
                return (new TrendResult)->trend([
                    'July 1' => 100,
                    'July 2' => 75,
                    'July 3' => 125,
                    'July 4' => 85,
                    'July 5' => 150,
                ]);
                break;
        }
    }

    public function previousDateRange($range, $timezone)
    {
        if ($range == 'TODAY') {
            return [
                now($timezone)->modify('yesterday')->setTime(0, 0),
                now($timezone)->subDays(1),
            ];
        }

        if ($range == 'MTD') {
            return [
                now($timezone)->modify('first day of previous month')->setTime(0, 0),
                now($timezone)->subMonthsNoOverflow(1),
            ];
        }

        if ($range == 'QTD') {
            return $this->previousQuarterRange($timezone);
        }

        if ($range == 'YTD') {
            return [
                now($timezone)->subYears(1)->firstOfYear()->setTime(0, 0),
                now($timezone)->subYearsNoOverflow(1),
            ];
        }

        return [
            now($timezone)->subDays($range * 2),
            now($timezone)->subDays($range),
        ];
    }

}
