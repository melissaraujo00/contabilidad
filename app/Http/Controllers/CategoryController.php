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
        return Inertia::render('category/Index', [
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        // Obtener todas las categorías (si es necesario, se puede modificar)
        $categories = Category::all(); // Si quieres pre-poblar con datos existentes, puedes hacer una consulta similar
        // Devolver la vista de Inertia con las categorías
        return Inertia::render('category/Create', [
            'categories' => $categories
        ]);
    }


    public function store(StoreCategoryRequest $request)
    {
        Category::query()->create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }

    //  función para verificar duplicados en tiempo real
    public function checkDuplicate(Request $request)
    {
        $name = $request->query('name');

        if (!$name) {
            return response()->json(['error' => 'El nombre es obligatorio'], 400);
        }

        $exists = Category::where('name', $name)->exists();

        return response()->json(['exists' => $exists]);
    }
}
