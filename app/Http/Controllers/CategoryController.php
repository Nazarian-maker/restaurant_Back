<?php

namespace App\Http\Controllers;

use App\Http\Filters\CategoryFilter;
use App\Http\Requests\Category\IndexRequest;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Category;
use App\Models\Dish;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public $service;

    public function __construct(CategoryService $service)
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
        $filter = app()->make(CategoryFilter::class, ['queryParams' => array_filter($data)]);
        $category = Category::filter($filter);

        $sort = $request->get('sort', 'asc');

        if ($category) {
            $category = $category->orderBy('name', $sort)->get();
            return $category;
        } else {
            return response()->json([
                'message' => 'Категории не найдены'
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', Category::class);
        try {
            $data = $request->validated();
            $data['category_picture'] = $this->service->store($request);
            $category = Category::create($data);

            if ($category) {
                return response([
                    'message' => 'Category Created Successfully',
                ], 200);
            } else {
                return response([
                    'message' => 'Category Can Not Be Created',
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
        $this->authorize('view', Category::class);
        $category = Category::find($id);

        if ($category) {
            return response([
                'category' => $category
            ], 200);
        } else {
            return response([
                'message' => "Данная категория не существует :("
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
        $this->authorize('update', Category::class);
        $data = $request->validated();
        $category = Category::find($id);

        if ($category) {
            if ($request->hasFile('category_picture')) {
                $data['category_picture'] = $this->service->store($request);
                $this->service->update($category);
            }
            $category->update($data);

            return response([
                'message' => "Категория была обновлена",
                'data' => $category
            ], 200);
        } else {
            return response([
                'message' => "Ошибка при обновлении категории"
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
        $this->authorize('delete', Category::class);
        $category = Category::find($id);
        if ($category) {
            $dishes = Dish::where('category_id', $id)->get();
            foreach ($dishes as $dish) {
                Storage::delete('public/'.$dish->dish_picture);
            }
            $this->service->delete($category);
            $category->delete();
            return response([
                'message' => 'Категория под номером ' . $category->id . ' была удалена!'
            ], 200);
        } else {
            return response([
                'message' => "Категория не существует :("
            ], 404);
        }
    }

    public function indexByCategory(Int $id) {
        $category = Category::findOrFail($id);
        $dishes = $category->dishes;
        if ($dishes) {
            return response([
                'Category dishes' => $dishes
            ], 200);
        } else {
            return response([
                'message' => "В данной категории нет блюд :("
            ], 404);
        }
    }
}
