<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'orders';

    protected $guarded = [];

    public function dishes() {
        return $this->belongsToMany(Dish::class, 'dish_orders', 'order_id', 'dish_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
