<?php

namespace Marispro\NovaDashboardManager\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\DateFilter;
use Laravel\Nova\Nova;
use Illuminate\Support\Carbon;


class DateRangeDefined extends DateFilter
{

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return __('Date Range');
    }

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

        $dateColumn = $query->getModel()->getCreatedAtColumn();
        if (isset($this->meta()['dateColumn'])) {
            $dateColumn = $this->meta()['dateColumn'] ?: $dateColumn;
        }
        if (isset($this->meta()['previousRange'])) {
            if ($this->meta()['previousRange'] == true) {
                return $query->whereBetween($dateColumn, $this->previousRange($value, $timezone));
            }
        }
//dd($this->currentRange($value, $timezone));
        return $query->whereBetween($dateColumn, $this->currentRange($value, $timezone));
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {

        return [
            'All' => 'ALL',
            '30 Days' => 30,
            '60 Days' => 60,
            '365 Days' => 365,
            'Today' => 'TODAY',
            'Month To Date' => 'MTD',
            'Quarter To Date' => 'QTD',
            'Year To Date' => 'YTD',
        ];

    }


    // copied from \Laravel\Nova\Metrics\Value::currentRange

    /**
     * Calculate the current range and calculate any short-cuts.
     *
     * @param string|int $range
     * @param string $timezone
     * @return array
     */
    protected function currentRange($range, $timezone)
    {
        if ($range == 'TODAY') {
            return [
                now($timezone)->today(),
                now($timezone),
            ];
        }

        if ($range == 'MTD') {
            return [
                now($timezone)->firstOfMonth(),
                now($timezone),
            ];
        }

        if ($range == 'QTD') {
            return $this->currentQuarterRange($timezone);
        }

        if ($range == 'YTD') {
            return [
                now($timezone)->firstOfYear(),
                now($timezone),
            ];
        }

        if ($range == 'ALL') {
            return [
                Carbon::createFromTimestamp(0),
                now($timezone),
            ];
        }

        return [
            now($timezone)->subDays($range),
            now($timezone),
        ];
    }

    /**
     * Calculate the previous quarter range.
     *
     * @param string $timezone
     *
     * @return array
     */
    protected function currentQuarterRange($timezone)
    {
        return [
            Carbon::firstDayOfQuarter($timezone),
            now($timezone),
        ];
    }


    /**
     * Calculate the previous range and calculate any short-cuts.
     *
     * @param string|int $range
     * @param string $timezone
     * @return array
     */
    protected function previousRange($range, $timezone)
    {
        if ($range == 'TODAY') {
            return [
                now($timezone)->modify('yesterday')->setTime(0, 0),
                now($timezone)->subDays(1),
            ];
        }

        if ($range == 'MTD') {
            return [
                now($timezone)->modify('first day of previous month')->setTime(0, 0),
                now($timezone)->subMonthsNoOverflow(1),
            ];
        }

        if ($range == 'QTD') {
            return $this->previousQuarterRange($timezone);
        }

        if ($range == 'YTD') {
            return [
                now($timezone)->subYears(1)->firstOfYear()->setTime(0, 0),
                now($timezone)->subYearsNoOverflow(1),
            ];
        }

        if ($range == 'ALL') {
            return [
                Carbon::createFromTimestamp(0),
                now($timezone),
            ];
        }

        return [
            now($timezone)->subDays($range * 2),
            now($timezone)->subDays($range),
        ];
    }

    /**
     * Calculate the previous quarter range.
     *
     * @param string $timezone
     *
     * @return array
     */
    protected function previousQuarterRange($timezone)
    {
        return [
            Carbon::firstDayOfPreviousQuarter($timezone)->setTimezone($timezone)->setTime(0, 0),
            now($timezone)->subMonthsNoOverflow(3),
        ];
    }

}
