<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    public const NUMBER = 'number';
    public const TOTAL_COUNT = 'total_cost';
    public const CLOSED_AT = 'closed_at';
    public const USER_ID = 'user_id';

    protected function getCallbacks(): array
    {
        return [
            self::NUMBER => [$this, 'number'],
            self::TOTAL_COUNT => [$this, 'totalCost'],
            self::CLOSED_AT => [$this, 'closedAt'],
            self::USER_ID => [$this, 'userId'],
        ];
    }

    public function number(Builder $builder, $value)
    {
        $builder->where('number', 'ilike', "%{$value}%");
    }

    public function totalCost(Builder $builder, $value)
    {
        $builder->where('total_cost', 'ilike', "%{$value}%");
    }

    public function closedAt(Builder $builder, $value)
    {
        $builder->where('closed_at', 'ilike', "%{$value}%");
    }

    public function userId(Builder $builder, $value)
    {
        $builder->where('user_id', 'ilike', "%{$value}%");
    }
}
