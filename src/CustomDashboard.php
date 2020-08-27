<?php

namespace Marispro\NovaDashboardManager;

use DigitalCreative\NovaDashboard\Dashboard;
use Illuminate\Support\Str;
use Marispro\NovaDashboardManager\Models\Dashboards;
use Marispro\NovaDashboardManager\Views\CustomView;
use Marispro\NovaDashboardManager\Models\Dashboards as DashboardModel;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use NovaBI\NovaDataboards\Models\Datafilter;
use NovaBI\NovaDataboards\Nova\Databoard;

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

    public static string $title = 'Custom Dashboardd';

    public static function humanize($value = null): string
    {
        return 'xzc';
    }



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
        //dd(Datafilter::whereFilterableId($this->model->id)->first()->filterable);

        $filterCards = [];
        $databoard = \NovaBI\NovaDataboards\Models\Databoard::find($this->model->id);
        // collect data filters
        $databoard->datafilters->each(function ($datafilter, $key) use (&$filterCards) {
            $filterCards[] = (new $datafilter->filterable->filter)->withMeta([]);
        });



        $headerCards = [];
        $widgetCards = [];
        //dd($filterCards);
        //return array_merge($headerCards, $filterPanel, $widgetCards);
        return [
            new CustomView($this->model),
            //CustomView::make()->editable()
                //->filters($filterCards)
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
