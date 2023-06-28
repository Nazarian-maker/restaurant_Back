<?php

namespace App\Http\Controllers;

use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\IndexRequest;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Dish;
use App\Models\DishOrder;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        $this->authorize('view', Order::class);
        $data = $request->validated();
        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);
        $order = Order::create($filter);

        $sortField = $request->get('sort_By', 'number');
        $sortOrder = $request->get('sort', 'asc');

        $order = $order->orderBy($sortField, $sortOrder)->get();

        if (!$order) {
            return response()->json([
                'message' => 'Заказ не найден'
            ], 404);
        }
        return $order;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Order::class);
        try {
            $data = $request->validated();
            $data['number'] = random_int(1, 999);
            $order = Order::create($data);

            if ($order) {
                return response([
                    'message' => 'Order Created Successfully',
                ], 200);
            } else {
                return response([
                    'message' => 'Order Can Not Be Created',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Order::class);
        $order = Order::find($id);
        if ($order) {
            return response([
                'order' => $order,
            ], 200);
        } else {
            return response([
                'message' => "Заказ не существует :("
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Int $id)
    {
        $this->authorize('update', Order::class);
        $data = $request->validated();
        $order = Order::find($id);
        if ($order) {
            if ($request['is_closed']) {
                $data['closed_at'] = Carbon::now();
            }
            $order->update($data);
            return response([
                'message' => "Заказ был обновлен"
            ], 200);
        } else {
            return response([
                'message' => "Ошибка обновления заказа :("
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id)
    {
        $this->authorize('delete', Order::class);
        $order = Order::destroy($id);
        if ($order) {
            return response([
                'message' => 'Заказ был удален!'
            ], 200);
        } else {
            return response([
                'message' => "Заказ не существует :("
            ], 404);
        }
    }

//    public function addDish(Request $request, int $orderId, int $dishId)
//    {
//        $this->authorize('update', Order::class);
//        $data = $request->validate([
//            'count' => 'required|numeric',
//            'closed_at' => 'date',
//        ]);
//        $order = Order::find($orderId);
//
//        if (!$order) {
//            return response()->json([
//                'message' => 'Заказ не найден'
//            ], 404);
//        }
//        $dish = Dish::find($dishId);
//
//        if (!$dish) {
//            return response()->json([
//                'message' => 'Блюдо не найдено'
//            ], 404);
//        }
//
//        if ($order->is_closed) {
//            return response()->json([
//                'message' => 'Заказ уже закрыт'
//            ], 400);
//        }
//
//        if (!$order->dishes()->find($dishId)) {
//            $order->dishes()->attach($order->id, $dishId, ['count' => $data['count']]);
//            $count = $order->count += $data['count'];
//            $total_cost = $order->total_cost += $dish->price * $data['count'];
//            $order->update(['count' => $count, 'total_cost' => $total_cost]);
//            return response()->json([
//                "message" => "Блюдо добавлено в заказ"
//            ], 200);
//        } else {
//            $dish_order = DishOrder::firstWhere([
//                ['dish_id', '=', $dishId],
//                ['order_id', '=', $order->id]
//            ]);
////            $count = $order->count - $dish_order->count;
////            $dish_order->count = $dish_order->count + $data['count'];
//        }
//    }
}
