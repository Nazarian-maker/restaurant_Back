<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    public const NUMBER = 'number';
    public const TOTAL_COUNT = 'total_cost';
    public const CLOSED_AT = 'closed_at';
    public const IS_CLOSED = 'is_closed';

    protected function getCallbacks(): array
    {
        return [
            self::NUMBER => [$this, 'number'],
            self::TOTAL_COUNT => [$this, 'total_cost'],
            self::CLOSED_AT => [$this, 'closed_at'],
            self::IS_CLOSED => [$this, 'is_closed'],
        ];
    }

    public function number(Builder $builder, $value)
    {
        $builder->where('number', 'ilike', "%{$value}%");
    }

    public function total_cost(Builder $builder, $value)
    {
        $builder->where('total_cost', 'ilike', "%{$value}%");
    }

    public function closed_at(Builder $builder, $value)
    {
        $builder->where('closed_at', 'ilike', "%{$value}%");
    }

    public function is_closed(Builder $builder, $value)
    {
        $builder->where('is_closed', 'ilike', "%{$value}%");
    }
}
