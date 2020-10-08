<?php

namespace Marispro\NovaDashboardManager\Models\Datafilterables;

class ActionEventTypes extends BaseDatafilterable
{
    // mapping to filter
    var $filter = \Marispro\NovaDashboardManager\Nova\Filters\ActionEventType::class;

    // supported card Widths
    var $cardWidthSupported = ['1/3'];
}
