<?php

namespace Marispro\NovaDashboard;

use DigitalCreative\NovaDashboard\Dashboard;
use DigitalCreative\NovaDashboard\DashboardTrait;
use DigitalCreative\NovaDashboard\NovaDashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Nova\Tool;
use Marispro\NovaDashboard\Models\Dashboards;
use NovaBI\NovaDataboards\Models\Databoard;
use NovaBI\NovaDataboards\NovaDataboards;

class DashboardManager extends NovaDashboard
{
    private array $dashboards;
    private bool $useNavigation = true;

    /**
     * Create a new element.
     *
     * @param array $dashboards
     */
    public function __construct(array $dashboards = [])
    {
        $this->dashboards = $dashboards;

    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return View|null
     */
    public function renderNavigation(): ?View
    {
        $dashboards = Dashboards::all();
        return view('nova-dashboard-manager::navigation', [ 'dashboards' => $dashboards ]);
    }

    public function getCurrentActiveDashboard(string $dashboardKey)
    {
        $dashboards = $this->resolveDashboards();
        $dashboardsProvided = Dashboards::all();
        foreach ($dashboardsProvided as $item) {
            $dashboards->put($item->name, $item );
        }

        foreach ($dashboards as $dashboard) {
            if ($dashboard->uriKey() === $dashboardKey) {
               return $dashboard;
            }
        }
        return null;

    }

    private function resolveDashboards(): Collection
    {
        return once(function () {
            return collect($this->dashboards)
                ->map(static function ($dashboard) {
                    return $dashboard instanceof Dashboard ? $dashboard : resolve($dashboard);
                })
                ->filter(static function (Dashboard $dashboard) {
                    return $dashboard->authorizedToSee(request());
                });
        });
    }
}
