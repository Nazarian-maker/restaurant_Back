<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class DishFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const COMPOSITION = 'composition';
    public const PRICE = 'price';
    public const CALORIES = 'calories';

    protected function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::COMPOSITION => [$this, 'composition'],
            self::CALORIES => [$this, 'calories'],
            self::PRICE => [$this, 'price'],
        ];
    }

    public function name(Builder $builder, $value)
    {
        $builder->where('name', 'ilike', "%{$value}%");
    }

    public function composition(Builder $builder, $value)
    {
        $builder->where('composition', 'ilike', "%{$value}%");
    }

    public function calories(Builder $builder, $value)
    {
        $builder->where('calories', 'ilike', "%{$value}%");
    }

    public function price(Builder $builder, $value)
    {
        $builder->where('price', 'ilike', "%{$value}%");
    }
}
