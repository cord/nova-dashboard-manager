<?php

namespace Marispro\NovaDashboardManager\Views;

use DigitalCreative\NovaDashboard\Examples\Actions\UniqueAction;
use DigitalCreative\NovaDashboard\Examples\Widgets\ExampleWidgetOne;
use DigitalCreative\NovaDashboard\View;
use Marispro\NovaDashboardManager\Models\Dashboards as DashboardModel;

class CustomView extends View
{
    private $databoard;

    function __construct(DashboardModel $dashboard)
    {
        //$this->dashboard = $dashboard;
        // todo: remove duplicated query. Can be done after merging with nova-bi. Need to move all morphable models to nova-dashboard-manager
        $this->databoard = \NovaBI\NovaDataboards\Models\Databoard::find($dashboard->id);
    }

    public function filters(): array
    {
        $filters = [];

        $this->databoard->datafilters->each(function ($datafilter, $key) use (&$filters) {
            $filters[] = (new $datafilter->filterable->filter)->withMeta([]);
        });
        return $filters;
    }

    public function actions(): array
    {
        // todo: how to get actions?
        return [
            new UniqueAction(),
        ];
    }

    public function widgets(): array
    {
        $widgets = [];

        $this->databoard->datawidgets->each(function ($datawidget, $key) use (&$widgets) {
            //dd($datawidget->metricable->visualable->getVisualisation());
            $widgets[] = (
                $datawidget->metricable->visualable->getVisualisation()
            )
                ->width($datawidget->metricable->visualable->cardWidth)
                ->withMeta(['widget_id' => $datawidget->id, 'label' => $datawidget->name]);
        });

        //dd($widgets);

        /* TODO: Need help to change instance type (again..)
         * Argument 1 passed to DigitalCreative\NovaDashboard\View::DigitalCreative\NovaDashboard\{closure}() must be an instance of DigitalCreative\NovaDashboard\Widget, instance of NovaBI\NovaDataboards\Models\Datavisualables\Visuals\Value given
         */

        return [];
    }

}
