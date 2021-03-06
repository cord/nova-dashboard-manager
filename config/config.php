<?php
declare(strict_types=1);

return [

    // want to show or hide the default tool menu?
    'showToolMenu' => true,

    'tables' => [
        'widget_configurations' => 'dwidget_configurations',
        'widgets' => 'dwidgets',
        'metrics' => 'dmetrics',
        'visuals' => 'dvisuals',
        'filters' => 'dfilters',
        'dashboards' => 'dboards',
    ],

    'models' => [
        'widget_configuration' => \DigitalCreative\NovaDashboard\Models\Widget::class,
        'widget' => \Marispro\NovaDashboardManager\Models\Datawidget::class,
        'filter' => \Marispro\NovaDashboardManager\Models\Datafilter::class,
        'dashboard' => \Marispro\NovaDashboardManager\Models\Dashboard::class
    ],


    'dashboardables' => [
        // Todo: make configurable
        'default' => 'todo',

        'resources' => [
            \Marispro\NovaDashboardManager\Nova\Dashboardables\Standard::class, // example dashboardable
        ],

        // TODO: load all resources from these paths
        'paths' => []

    ],

    /*
     * register the available filters which can be configured for each dashboard
     */
    'datafilterables' => [
        // Todo: make configurable
        'default' => 'todo',

        'resources' => [
            \Marispro\NovaDashboardManager\Nova\Datafilterables\DateFrom::class,
            \Marispro\NovaDashboardManager\Nova\Datafilterables\DateTo::class,
            \Marispro\NovaDashboardManager\Nova\Datafilterables\ActionEventTypes::class,

        ],

        // TODO: load all resources from these paths
        'paths' => []
    ],

    /*
     * register the available metrics which can be configured for each dashboard
     */

    'datametricables' => [
        // Todo: make configurable
        'default' => 'todo',

        'resources' => [
            \Marispro\NovaDashboardManager\Nova\Datametricables\users::class, // example dashboardable
            \Marispro\NovaDashboardManager\Nova\Datametricables\boards::class, // example dashboardable
            \Marispro\NovaDashboardManager\Nova\Datametricables\widgets::class, // example dashboardable
            \Marispro\NovaDashboardManager\Nova\Datametricables\actionEvents::class, // example dashboardable
        ],

        // TODO: load all resources from these paths
        'paths' => []
    ],

    /*
     * register the available visuals which can be configured for each metric
     */
    'datavisualables' => [
        // Todo: make configurable
        'default' => 'todo',

        /*
         * by using names you can later re-configure the visualisation for e.g. "Value" when there are new visualisation types available
         * in you metricable the types can be limit with short-names:
         *      var $visualisationTypes = ['Value', 'Trend'];
         */
        'resources' => [
            'Value' => \Marispro\NovaDashboardManager\Nova\Datavisualables\Value::class,
//            'Trend' => \Marispro\NovaDashboardManager\Nova\Datavisualables\Trend::class,
//            'Partition' => \Marispro\NovaDashboardManager\Nova\Datavisualables\Partition::class
        ],

        // TODO: load all resources from these paths
        'paths' => []
    ],

];
