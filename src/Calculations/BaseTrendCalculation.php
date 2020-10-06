<?php


namespace Marispro\NovaDashboardManager\Calculations;


use Laravel\Nova\Metrics\Trend;
use Marispro\NovaDashboardManager\Models\Dashboard;

abstract class BaseTrendCalculation extends Trend
{
    use Calculatable;
}