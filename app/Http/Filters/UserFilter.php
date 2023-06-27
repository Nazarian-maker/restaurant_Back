<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class UserFilter extends AbstractFilter
{
    public const NAME = 'name';
    public const EMAIL = 'email';
    public const ROLE = 'role_id';

    protected function getCallbacks(): array
    {
        return [
            self::NAME => [$this, 'name'],
            self::EMAIL => [$this, 'email'],
            self::ROLE => [$this, 'role'],
        ];
    }

    public function name(Builder $builder, $value)
    {
        $builder->where('name', 'ilike', "%{$value}%");
    }

    public function email(Builder $builder, $value)
    {
        $builder->where('email', 'ilike', "%{$value}%");
    }

    public function role(Builder $builder, $value)
    {
        $builder->where('role_id', 'ilike', "%{$value}%");
    }
}
