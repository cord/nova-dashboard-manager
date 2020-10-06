<?php

namespace Marispro\NovaDashboardManager\Models\Datametricables;


use DigitalCreative\NovaDashboard\Examples\Filters\Category;
use DigitalCreative\NovaDashboard\Examples\Filters\Date;
use DigitalCreative\NovaDashboard\Examples\Filters\Quantity;
use DigitalCreative\NovaDashboard\Filters;
use Illuminate\Support\Collection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Marispro\NovaDashboardManager\Calculations\UserCalculation;
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
                
                $calcuation = UserCalculation::make();

                if ($this->only_verified_email) {
                    $calcuation = $calcuation->verified();
                } else {
                    $calcuation = $calcuation;
                }

                // option 1
                // get filter values and calculate result
                $dateValue = $filters->getFilterValue(DateRangeDefined::class);


                $calcuation->applyFilter($filters, DateRangeDefined::class,

//                    ['dateColumn' => 'asdfg']
                
                );

                $query = $calcuation->query();


                

//                $quantityValue = $filters->getFilterValue(Quantity::class);
//                $vategoryValue = $filters->getFilterValue(Category::class);

                


                return [
                    'currentValue' => $query->get()->count(),
                    'previousValue' => $query->get()->count()
                ];

                // use internal methods
//                return $visual->count($request, $filteredModel)->suffix($prefix . 'Users');

                // calculation
                /*
                    return $visual
                        ->result($filteredModel->count())
                        ->previous((new User)->count() / 2, 'All')
                        ->prefix('Boards ')
                        ->suffix('for fun')->withoutSuffixInflection();
                */
                break;

            case \Marispro\NovaDashboardManager\Models\Datavisualables\LineChart::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Trend
                 */
                $filteredModel = $visual->globalFiltered((new User)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                ]);
                return $visual->countByDays($request, $filteredModel)->showLatestValue();

                break;
            case \Marispro\NovaDashboardManager\Models\Datavisualables\Partition::class :
                /**
                 * @var $visual \Laravel\Nova\Metrics\Partition
                 */
                $filteredModel = $visual->globalFiltered((new User)->newQuery(), [
                    DateFilterFrom::class,
                    DateFilterTo::class,
                ]);

                // Bug in Nova
                // https://github.com/laravel/nova-issues/issues/2681

                $result = $visual->count($request, User::class, 'email_verified_at')
                    ->label(function ($value) {
                        switch ($value) {
                            case null:
                                return 'Not verified';
                            default:
                                return 'verified';
                        }
                    });
                return $result;
                break;
        }
    }

}
