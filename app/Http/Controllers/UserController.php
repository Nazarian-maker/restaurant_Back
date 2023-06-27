<?php

namespace App\Http\Controllers;

use App\Http\Filters\UserFilter;
use App\Http\Requests\User\IndexRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(IndexRequest $request)
    {
        $this->authorize('view', auth()->user());

        $data = $request->validated();
        $filter = app()->make(UserFilter::class, ['queryParams' => array_filter($data)]);
        $user = User::filter($filter);

        $sortField = $request->get('sort_By', 'name');
        $sortOrder = $request->get('sort', 'asc');

        $user = $user->orderBy($sortField, $sortOrder);

        $user = $user->get();

        if (!$user) {
            return response()->json([
                'message' => 'Пользователи не найдены'
            ], 404);
        }
        return $user;
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('create', auth()->user());
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            if ($user) {
                return response([
                    'message' => 'User Created Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            } else {
                return response([
                    'message' => 'Can not create user :(',
                ], 500);
            }
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        $this->authorize('view', auth()->user());
        $user = User::find($id);
        if ($user) {
            return response([
                'user' => $user
            ], 200);
        } else {
            return response([
                'message' => "Пользователь не существует :("
            ], 404);
        }
    }

    public function update(UpdateRequest $request, int $id)
    {
        $this->authorize('update', auth()->user());

        $data = $request->validated();
        $user = User::find($id);
        $user->update($data);
        return response([
            'message' => "Пользователь был обновлен"
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->authorize('delete', auth()->user());
        $user = User::destroy($id);
        if ($user) {
            return response([
                'message' => 'Пользователь был удален!'
            ], 200);
        } else {
            return response([
                'message' => "Пользователь не существует :("
            ], 404);
        }
    }
}
