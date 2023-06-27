<?php

namespace App\Http\Controllers;

use App\Http\Filters\DishFilter;
use App\Http\Requests\Dish\IndexRequest;
use App\Http\Requests\Dish\StoreRequest;
use App\Http\Requests\Dish\UpdateRequest;
use App\Models\Category;
use App\Models\Dish;
use App\Services\DishService;

class DishController extends Controller
{
    public $service;

    public function __construct(DishService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        $data = $request->validated();

        $filter = app()->make(DishFilter::class, ['queryParams' => array_filter($data)]);
        $dish = Dish::filter($filter);

        $sortField = $request->get('sort_By', 'name');
        $sortOrder = $request->get('sort', 'asc');
        $dish = $dish->orderBy($sortField, $sortOrder)->get();

        if (!$dish) {
            return response()->json([
                'message' => 'Категории не найдены'
            ], 404);
        }
        return $dish;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Dish::class);
        try {
            $data = $request->validated();
            $data['dish_picture'] = $this->service->store($request);
            $dish = Dish::create($data);

            if ($dish) {
                return response([
                    'message' => 'Dish Created Successfully',
                ], 200);
            } else {
                return response([
                    'message' => 'Dish Can Not Be Created',
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
    public function show(int $id)
    {
        $this->authorize('view', Dish::class);
        $dish = Dish::find($id);
        if ($dish) {
            return response([
                'category' => $dish
            ], 200);
        } else {
            return response([
                'message' => "Данное блюдо не существует :("
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
        $this->authorize('update', Dish::class);
        $data = $request->validated();
        $dish = Dish::find($id);

        if ($dish) {
            if ($request->hasFile('dish_picture')) {
                $data['dish_picture'] = $this->service->store($request);
                $this->service->update($dish);
            }
            $dish->update($data);

            return response([
                'message' => "Блюдо было обновлено",
                'data' => $dish
            ], 200);
        } else {
            return response([
                'message' => "Ошибка при обновлении блюд"
            ], 404);
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
        $this->authorize('delete', Dish::class);
        $dish = Dish::find($id);

        if ($dish) {
            $this->service->delete($dish);
            $dish->delete();
            return response([
                'message' => 'Категория под номером ' . $dish->id . ' была удалена!'
            ], 200);
        } else {
            return response([
                'message' => "Категория не существует :("
            ], 404);
        }
    }

    public function menu()
    {
        $categories = Category::all()->each->dishes;
        return response()->json([
            'data' => $categories
        ], 200);
    }
}
