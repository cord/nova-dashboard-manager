<?php

namespace Marispro\NovaDashboardManager;

use DigitalCreative\NovaDashboard\Dashboard;
use DigitalCreative\NovaDashboard\NovaDashboard;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Marispro\NovaDashboardManager\Models\Dashboards;

class DashboardManager extends NovaDashboard
{

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
            return Dashboards::all()
                             ->filter(fn(Dashboards $dashboard) => $dashboard->authorizedToSee(request()))
                             ->mapInto(CustomDashboard::class);
        });
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
