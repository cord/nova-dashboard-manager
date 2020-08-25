<?php

namespace Marispro\NovaDashboard;

use DigitalCreative\NovaDashboard\Dashboard;
use DigitalCreative\NovaDashboard\Examples\Views\AnotherView;
use Marispro\NovaDashboard\Models\Dashboards as DashboardModel;

class CustomDashboard extends Dashboard
{

    private DashboardModel $model;

    /**
     * @param DashboardModel $dashboards
     */
    public function __construct(DashboardModel $dashboards)
    {
        $this->model = $dashboards;
    }

    public static string $title = 'Custom Dashboard';

    public function views(): array
    {
        /**
         * Here you have access to $this->model ... so you can build any custom view dynamically...
         * you can also pass the same model down to the custom views to build the widgets dynamically too
         */
        return [
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
