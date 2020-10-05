<?php

namespace Marispro\NovaDashboardManager\Models\Datavisualables;

class LineChart extends BaseDatavisualable
{
    // mapping to visual
    var $visual = \Marispro\NovaDashboardManager\Models\Datavisualables\Visuals\LineChart::class;


    public static function getResourceModel() {
        return \Marispro\NovaDashboardManager\Nova\Datavisualables\LineChart::class;
    }


}
