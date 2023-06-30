<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Order;
use Illuminate\Http\Request;

class DishOrderController extends Controller
{
    public function addDish(Request $request, int $orderId)
    {
        $this->authorize('update', Order::class);
        $data = $request->validate([
            'dish_id' => 'required|integer',
            'count' => 'required|integer',
        ]);
        $order = Order::findOrFail($orderId);

        if ($order->is_closed) {
            return response()->json([
                'message' => 'Заказ уже закрыт'
            ], 400);
        }
        $dish = Dish::findOrFail($data['dish_id']);
        // проверяем, существует ли уже данное блюдо в заказе
        $orderDish = $order->dishes()->wherePivot('dish_id', $data['dish_id'])->first();

        if ($orderDish) {
            // обновляем данные уже существующей связи
            $newCount = $data['count'] + $orderDish->pivot->count;
            $order->dishes()->updateExistingPivot($data['dish_id'], ['count' => $newCount]);
            $order->count += $data['count'];
            $order->total_cost += $dish['price'] * $data['count'];
            $order->save();
            return response()->json([
                'message' => 'Заказ успешно обновлен',
                'dish' => $dish->name,
                'count' => $newCount,
            ], 200);
        } else {
            // добавляем новую связь
            $order->total_cost += $dish['price'] * $data['count'];
            $order->count += $data['count'];
            $order->dishes()->attach($order->id, ['count' => $data['count'], 'dish_id' => $data['dish_id']]);
            $order->save();
            return response()->json([
                'message' => 'Блюдо добавлено в заказ',
                'dish' => $dish->name,
                'count' => $data['count'],
            ], 200);
        }
    }

    public function deleteDish(Request $request, int $orderId, int $dishId)
    {
        $this->authorize('update', Order::class);
        $data = $request->validate([
            'count' => 'required|integer',
        ]);
        $order = Order::findOrFail($orderId);

        if ($order->is_closed) {
            return response()->json([
                'message' => 'Заказ уже закрыт'
            ], 400);
        }
        $dish = Dish::findOrFail($dishId);
        // проверяем, существует ли уже данное блюдо в заказе
        $orderDish = $order->dishes()->where('dish_id', $dishId)->first();

        if (!$orderDish) {
            return response()->json([
                "message" => "Блюдо в заказе не найдено"
            ], 404);
        }
        $newCount = $orderDish->pivot->count - $data['count'];
        if ($newCount > 0) {
            $order->dishes()->updateExistingPivot($dishId, ['count' => $newCount]);
            $order->count -= $data['count'];
            $order->total_cost -= $dish->price * $data['count'];
            $order->save();
            return response()->json([
                "message" => "Блюдо удалено из заказа"
            ], 200);
        } elseif ($newCount == null) {
            $order->dishes()->detach($dishId);
            $order->count -= $data['count'];
            $order->total_cost -= $dish->price * $data['count'];
            $order->save();
            return response()->json([
                "message" => "Блюдо удалено из заказа"
            ], 200);
        } else {
            return response()->json([
                "message" => "Превышено число количества блюда в заказе на ".-$newCount
            ]);
        }
    }
}
