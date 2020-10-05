<?php

namespace Marispro\NovaDashboardManager\Models\Datavisualables;

class BarChart extends BaseDatavisualable
{
    // mapping to visual
    var $visual = \Marispro\NovaDashboardManager\Models\Datavisualables\Visuals\BarChart::class;


    public static function getResourceModel() {
        return \Marispro\NovaDashboardManager\Nova\Datavisualables\BarChart::class;
    }


}
