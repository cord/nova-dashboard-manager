<?php

namespace Marispro\NovaDashboardManager\Nova\Datafilterables;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;

class DateRange extends BaseFilter
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \Marispro\NovaDashboardManager\Models\Datafilterables\DateRange::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function filterFields(Request $request)
    {
        return [
//            Date::make(__('Default Date To'), 'default_to'),
        ];
    }
}
