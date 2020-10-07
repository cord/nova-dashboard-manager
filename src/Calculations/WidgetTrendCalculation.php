<?php

namespace Marispro\NovaDashboardManager\Calculations;

use Marispro\NovaDashboardManager\Models\Datawidget;

class WidgetTrendCalculation extends BaseTrendCalculation
{

    /**
     * Create a new base calculation.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return (new Datawidget())->newQuery();
    }

}