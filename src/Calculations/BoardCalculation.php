<?php


namespace Marispro\NovaDashboardManager\Calculations;


use Marispro\NovaDashboardManager\Models\Dashboard;

class BoardCalculation extends BaseCalculation
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

    /*
     * Calculations
     *
     *
     */

    /*
     * Total number of users
     *
     */
    public function totalQuery()
    {
        return $this->newQuery();
    }
}