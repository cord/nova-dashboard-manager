<?php


namespace Marispro\NovaDashboardManager\Models\Datafilterables;

use Illuminate\Support\Str;
use Marispro\NovaDashboardManager\Traits\HasSchemalessAttributesTrait;
use Illuminate\Database\Eloquent\Model;


class BaseDatafilterable extends Model
{
    use HasSchemalessAttributesTrait;

    public $timestamps = true;

    // mapping to Nova filter
    var $filter;



    public $casts = [
        'extra_attributes' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(Str::singular(config('nova-dashboard-manager.tables.filters')) . '_standard');
    }

}
