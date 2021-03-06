<?php

namespace Marispro\NovaDashboardManager;

use DigitalCreative\NovaDashboard\NovaDashboard;
use DigitalCreative\NovaDashboard\Dashboard;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Marispro\NovaDashboardManager\Models\Dashboard as DashboardModel;
use Marispro\NovaDashboardManager\Nova\DashboardConfiguration;
use Marispro\NovaDashboardManager\Nova\Datafilter;
use Marispro\NovaDashboardManager\Nova\Datawidget;

class DashboardManager extends NovaDashboard
{

    /**
     * @var mixed
     */
    public $dashboardConfigurationResource = DashboardConfiguration::class;

    /**
     * @var mixed
     */
    public $datawidgetResource = Datawidget::class;
    /**
     * @var mixed
     */
    public $datafilterResource = Datafilter::class;
    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return View|null
     */
    public function renderNavigation(): ?View
    {
        return view('nova-dashboard-manager::navigation', [ 'dashboards' => $this->resolveDashboards() ]);
    }

    protected function resolveDashboards(): Collection
    {
        return once(static function () {
            return DashboardModel::all()
                             ->filter(fn(DashboardModel $dashboard) => $dashboard->authorizedToSee(request()))
                             ->mapInto(CustomDashboard::class);
        });
    }

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
//        Nova::script('nova-dashboard-manager', __DIR__ . '/../dist/js/tool.js');

        Nova::resources([
            $this->dashboardConfigurationResource,
            $this->datawidgetResource,
            $this->datafilterResource,
        ]);
        Nova::resources(config('nova-dashboard-manager.dashboardables.resources'));
        Nova::resources(config('nova-dashboard-manager.datafilterables.resources'));
        Nova::resources(config('nova-dashboard-manager.datametricables.resources'));
        Nova::resources(config('nova-dashboard-manager.datavisualables.resources'));
    }

    public function getCurrentActiveDashboard(string $dashboardKey): ?Dashboard
    {
        /**
         * @var Dashboard $dashboard
         */
        foreach ($this->resolveDashboards() as $dashboard) {
            if ($dashboard->resourceUri() === $dashboardKey) {
                if (is_string($dashboard) && class_exists($dashboard)) {
                    return new $dashboard();
                }
                return $dashboard;
            }
        }

        return null;

    }

}
