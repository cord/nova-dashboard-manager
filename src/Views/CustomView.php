<?php

namespace Marispro\NovaDashboardManager\Views;

use DigitalCreative\NovaDashboard\Action;
use DigitalCreative\NovaDashboard\Examples\Actions\UniqueAction;
use DigitalCreative\NovaDashboard\Examples\Widgets\ExampleWidgetOne;
use DigitalCreative\NovaDashboard\View;
use DigitalCreative\NovaDashboard\Widget;
use Illuminate\Support\Collection;
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

    public function titler($title = null)
    {
        return $this->databoard->name;
    }

    private function resolveActions(): Collection
    {
        return once(function () {
            return collect($this->actions())->filter(function (Action $action) {
                return $action->authorizedToSee(request());
            });
        });
    }

    private function resolveSchemas(): Collection
    {
        return $this->resolveWidgets()
            ->mapWithKeys(function (Widget $widget) {
                return [
                    $widget->uriKey() => $widget->getSchema(),
                ];
            });
    }

    private function resolveWidgets(): Collection
    {
        return once(function () {
            return collect($this->widgets())->filter(function (Widget $widget) {
                return $widget->authorizedToSee(request());
            });
        });
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->titler(),
            'uriKey' => $this->uriKey(),
            'filters' => $this->resolveFilters(),
            'actions' => $this->resolveActions(),
            'schemas' => $this->resolveSchemas(),
            'meta' => $this->meta(),
        ];
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
