<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function store($data) {
        if ($data->hasFile('category_picture')) {
            $file = $data->file('category_picture');
            $path = $file->store('images', 'public');
            return $path;
        }
    }

    public function update($category) {
        Storage::delete('public/' . $category->category_picture);
    }

    public function delete($category) {
        Storage::delete('public/' . $category->category_picture);
    }
}
