<?php

namespace Marispro\NovaDashboardManager;

use DigitalCreative\NovaDashboard\Dashboard;
use DigitalCreative\NovaDashboard\Examples\Views\AnotherView;
use Marispro\NovaDashboardManager\Models\Dashboards as DashboardModel;

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

    /**
     * Get the URI for the resource.
     *
     * @return string
     */
    public function resourceUri(): string
    {
        return "custom-dashboard-{$this->model->id}";
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public function resourceLabel(): string
    {
        return $this->model->name;
    }

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
