<?php

namespace Marispro\NovaDashboardManager\Nova;

use App\Nova\Situation;
use Marispro\NovaDashboardManager\Nova\Dashboardables\BaseFilter;

use Laravel\Nova\Resource;

use Marispro\NovaDashboardManager\Traits\LoadMorphablesTrait;

use Digitalazgroup\PlainText\PlainText;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use DigitalCreative\InlineMorphTo\InlineMorphTo;
use DigitalCreative\InlineMorphTo\HasInlineMorphToFields;
use NovaAttachMany\AttachMany;
use Pdmfc\NovaCards\Info;
use Saumini\Count\RelationshipCount;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;


class DashboardConfiguration extends Resource
{
//    public static $displayInNavigation = false;

    use HasSortableRows;
    use HasInlineMorphToFields;
    use LoadMorphablesTrait;


//    use TabsOnEdit;

    // Use this Trait

    public static $defaultSortField = 'sort_order';

    public static $group = 'Databoard';

    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = \Marispro\NovaDashboardManager\Models\Dashboard::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'name'
    ];

    /**
     * Get the displayable label of the resource.
     *
     * @return  string
     */
    public static function label()
    {
        return __('Databoard Configuration');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return  string
     */
    public static function singularLabel()
    {
        return __('Databoard Configuration');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function fields(Request $request)
    {

        $dashboardables = config('nova-dashboard-manager.dashboardables.resources');

        /*
         * todo: autoload from config('nova-dashboard-manager.dashboardables.paths')
        $dashboardables = $this->loadMorphables(config('nova-dashboard-manager.dashboardables'));
        $dashboardables = array_filter($dashboardables, function ($boardable) {
            return class_basename($boardable) != 'BaseBoard';
        });
        */

        $fields = [
            InlineMorphTo::make(__('Board Type'), 'dashboardable')
                ->types($dashboardables)->required()->hideFromIndex()
//                ->default(),
        ];

        return
            array_merge(
                [
                    Text::make(__('Name'), 'name'),
                    Textarea::make(__('Description'), 'description')
                        ->alwaysShow()
                        ->rows(3)
                        ->withMeta(['extraAttributes' => [
                            'placeholder' => __('Provide a short description for internal use')]
                        ])
                        ->help(
                            'Internal Description'
                        ),
                ],
                $fields,
                [
                    PlainText::make(__('Databoard Type'), function () {
                        if (method_exists($this->dashboardable, 'label')) {
                            return $this->dashboardable->label();
                        }
                        return '';
                    }),


                    AttachMany::make(__('Filters'), 'datafilters', Datafilter::class)
                        ->rules('min:1')
                        ->showCounts()
                        ->help('Select a Filters to attach')->onlyOnForms(),

                    AttachMany::make(__('Widgets'), 'datawidgets', Datawidget::class)
                        ->rules('min:1')
                        ->showCounts()
                        ->help('Select a Widgets to attach')->onlyOnForms(),

                    RelationshipCount::make('Data Widgets', 'datawidgets')->onlyOnIndex(),
                    RelationshipCount::make('Data Filters', 'datafilters')->onlyOnIndex(),

                    (new Tabs('Relations', [
                        'Data Widgets' => [
                            BelongsToMany::make('datawidgets')
                                ->rules('required')

                        ],
                        'Data Filters' => [
                            BelongsToMany::make('datafilters')
                                ->rules('required')

                        ]
                    ]))->defaultSearch(true),

                ]
            );

    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function cards(Request $request)
    {
        $cards = [];
        if (\Marispro\NovaDashboardManager\Models\Datawidget::count() == 0) {
            $cards[] =(new Info())->info(__('Please <a href="databoardWidget" class="text-primary dim no-underline">configure your first Widget</a>'))->asHtml();
        }
        if (\Marispro\NovaDashboardManager\Models\Datafilter::count() == 0) {
            $cards[] =(new Info())->info(__('Please <a href="databoardFilter" class="text-primary dim no-underline">configure your first Filter</a>'))->asHtml();
        }
        return $cards;
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [];
    }
    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'dashboard-configuration';
    }

    public static function availableForNavigation(Request $request)
    {
        return (config('nova-dashboard-manager.showToolMenu') === false);
    }
}
