<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(20);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }


    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return to_route('categories.index');
    }

    public function edit(Category $category)
    {

    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {

    }

    public function destroy(Category $category)
    {

    }


}
