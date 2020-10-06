<?php

namespace Marispro\NovaDashboardManager\Models\Datavisualables\Visuals;

use DigitalCreative\NovaDashboard\Filters;
use DigitalCreative\ChartJsWidget\Color;
use DigitalCreative\ChartJsWidget\DataSet;
use DigitalCreative\ChartJsWidget\Gradient;
use DigitalCreative\ChartJsWidget\LineChartWidget;
use DigitalCreative\ChartJsWidget\Style;
use DigitalCreative\ChartJsWidget\ValueResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Select;
use Marispro\NovaDashboardManager\Traits\DynamicMetricsTrait;
use Illuminate\Http\Request;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class LineChart extends LineChartWidget
{
//    use DynamicMetricsTrait;
//    use GlobalFilterable;

    var $baseUriKey = 'value';

    public static $title = 'my LineChartWidget';


    public function getRandomData($min = 0, $max = 100): array
    {
        return array_rand(range($min, $max), 12);
    }


    public function getMonthsInTheYear(): array
    {

        return array_map(static function ($month) {
            return now()->startOfMonth()->setMonth($month)->format('M');
        }, range(1, 12));

    }

    public function _resolveValue(Collection $options, Filters $filters): ValueResult
    {

        /**
         * Some basic stylish settings
         */
        $dataset1Gradient = new Gradient(['#bc00dd', '#a100f2', '#540d6e']);
        $dataset2Gradient = new Gradient(['#8d99ae', '#33415c']);

        $style = Style::make();

        $dataSet1 = DataSet::make(
            'Downloads',
            $this->getRandomData(0, 30),
            $style->color($dataset1Gradient)
                ->background($dataset1Gradient->opacity(.1))
        );

        $dataSet2 = DataSet::make(
            'Purchases',
            $this->getRandomData(),
            $style->color($dataset2Gradient)
                ->background($dataset2Gradient->opacity(.1))
        );

        return ValueResult::make()
            ->labels($this->getMonthsInTheYear())
            ->addDataset($dataSet1)
            ->addDataset($dataSet2);

    }

    public const STYLE = 'style';
    public const STYLE_BLUE = 'blue';
    public const STYLE_YELLOW = 'yellow';

    public function resolveValue(Collection $options, Filters $filters): ValueResult
    {
        $configuration = Style::make();


        $result = $this->meta['metric']->calculate($options, $filters);

        $valueResult = ValueResult::make()
            ->labels($result['labels']);

        foreach ($result['datasets'] as $label => $dset) {
            $dataSet = DataSet::make($label, $dset, $configuration);
            $valueResult->addDataset($dataSet);
        }
        return $valueResult;


        ///-------------------
        $style = $options->get(self::STYLE);

        /**
         * Some basic stylish settings
         */
        $configuration = Style::make();

        if ($style === self::STYLE_YELLOW) {

            /**
             * You can either use the array syntax or a Gradient object
             */
            $colorColor = ['#FAD961', '#F76B1C'];
            $backgroundColor = ['#FAD961', '#F76B1C'];
            $configuration->pointRadius(0);

        } else {

            $colorColor = '#005bea';
            $backgroundColor = ['rgba(0, 91, 234,.8)', 'rgba(255,255,255,0)', Gradient::VERTICAL];

        }


        $configuration = $configuration
            ->color($colorColor)
            ->background($backgroundColor);

        $dataSet = DataSet::make('Sample Label', $this->getRandomData(), $configuration);

        return ValueResult::make()
            ->labels($this->getMonthsInTheYear())
            ->addDataset($dataSet);


    }

    public function fields(): array
    {

        return array_merge([

            Select::make('Style', self::STYLE)->options([
                self::STYLE_BLUE => 'Style 1',
                self::STYLE_YELLOW => 'Style 2'
            ])

        ], parent::fields());

    }


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public function label(): string
    {
        return 'my label';
    }


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->meta('title');
    }

    public function uriKey(): string
    {
        return $this->meta('uriKey');
    }
}
