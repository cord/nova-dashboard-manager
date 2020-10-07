<?php

namespace Marispro\NovaDashboardManager\Calculations;

use Marispro\NovaDashboardManager\Models\Dashboard;

class BoardTrendCalculation extends BaseTrendCalculation
{

    /**
     * Create a new base calculation.
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return (new Dashboard())->newQuery();
    }

}