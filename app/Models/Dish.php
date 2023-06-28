<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'dishes';

     protected $fillable = [
         'name',
         'dish_picture',
         'composition',
         'calories',
         'price',
         'category_id',
     ];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function orders() {
        return $this->belongsToMany(Order::class, 'dish_orders', 'dish_id', 'order_id');
    }
}
