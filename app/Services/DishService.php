<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class DishService
{
    public function store($data) {
        if ($data->hasFile('dish_picture')) {
            $file = $data->file('dish_picture');
            $path = $file->store('images', 'public');
            return $path;
        }
    }

    public function update($dish) {
        Storage::delete('public/' . $dish->dish_picture);
    }

    public function delete($dish) {
        Storage::delete('public/' . $dish->dish_picture);
    }
}
