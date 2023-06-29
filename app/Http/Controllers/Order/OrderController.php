<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\Order\IndexRequest;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Carbon\Carbon;

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
        $order = Order::filter($filter);

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
            return response( new OrderResource($order), 200);
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
    public function update(UpdateRequest $request, int $id)
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
    public function destroy(int $id)
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
}
