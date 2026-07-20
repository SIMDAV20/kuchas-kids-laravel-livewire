<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        abort_unless($category->status == Category::PUBLIC, 404);

        return view('categories.show', compact('category'));
    }
}
