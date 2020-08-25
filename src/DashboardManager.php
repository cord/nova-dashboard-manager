<?php

namespace Marispro\NovaDashboard;

use DigitalCreative\NovaDashboard\NovaDashboard;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Marispro\NovaDashboard\Models\Dashboards;

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

}
