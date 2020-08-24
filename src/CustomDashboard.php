<?php

namespace Marispro\NovaDashboardManager;

use DigitalCreative\NovaDashboard\Dashboard;
use DigitalCreative\NovaDashboard\DashboardTrait;
use DigitalCreative\NovaDashboard\Examples\Views\AnotherView;
use DigitalCreative\NovaDashboard\Examples\Views\ProductsSalesView;
use Marispro\NovaDashboardManager\DashboardManagerTrait;
use Marispro\NovaDashboardManager\Models\Dashboards;

class CustomDashboard extends Dashboard
{
    use DashboardManagerTrait, DashboardTrait {
        DashboardManagerTrait::title insteadof DashboardTrait;
        DashboardManagerTrait::humanize insteadof DashboardTrait;
        DashboardManagerTrait::label insteadof DashboardTrait;
        DashboardManagerTrait::uriKey insteadof DashboardTrait;
        DashboardManagerTrait::meta insteadof DashboardTrait;
    }

    public static string $title = 'Custom Dashboard';



    public function views(): array
    {
        return [

//new Dashboards() // set id to current dashboard
            AnotherView::make()->editable()
        ];
    }

    public function options(): array
    {
        return [
            'expandFilterByDefault' => true,
            'grid' => [
                'compact' => true,
            ]
        ];
    }

}
