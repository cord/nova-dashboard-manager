<?php

namespace Marispro\NovaDashboardManager\Models\Datavisualables\Visuals;

use DigitalCreative\ChartJsWidget\BarChartWidget;
use DigitalCreative\ChartJsWidget\BarChatStyle;
use DigitalCreative\NovaDashboard\Filters;
use DigitalCreative\ChartJsWidget\Color;
use DigitalCreative\ChartJsWidget\DataSet;
use DigitalCreative\ChartJsWidget\Gradient;
use DigitalCreative\ChartJsWidget\LineChartWidget;
use DigitalCreative\ChartJsWidget\Style;
use DigitalCreative\ChartJsWidget\ValueResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Marispro\NovaDashboardManager\Traits\DynamicMetricsTrait;
use Illuminate\Http\Request;
use Nemrutco\NovaGlobalFilter\GlobalFilterable;

class BarChart extends BarChartWidget {
//    use DynamicMetricsTrait;
//    use GlobalFilterable;

    var $baseUriKey = 'value';

    public static $title = 'my LineChartWidget';

    /**
     * Calculate the value of the metric.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */

    public function metricCalculate(Collection $options, Filters $filters, $visual)
    {
        return $this->meta['metric']->calculate($options, $filters, $visual);
    }

    public function getRandomData($min = 1, $max = 100): array
    {
        return [
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
            random_int($min, $max),
        ];
    }
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

        $configuration = BarChatStyle::make()
            ->hoverBackgroundColor('green');

        $dataSet1 = DataSet::make('Sample A', $this->getRandomData(), $configuration);
        $dataSet2 = DataSet::make('Sample B', $this->getRandomData(), $configuration);
        $dataSet3 = DataSet::make('Sample C', $this->getRandomData(), $configuration);
        $dataSet4 = DataSet::make('Sample D', $this->getRandomData(), $configuration);

        return $this->value()
            ->labels($this->getRandomData())
            ->addDataset($dataSet1, $dataSet2, $dataSet3, $dataSet4);

    }

    public function defaults(): array
    {
        return [
            'layout' => [
                'padding' => [
                    'left' => 50,
                    'right' => 50,
                    'top' => 50,
                    'bottom' => 50,
                ]
            ],
            'legend' => [
                'display' => false
            ]
        ];
    }


    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public  function label(): string
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
