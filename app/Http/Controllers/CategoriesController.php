<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:categories|max:255',
            'pictureUrl' => 'nullable|string',
        ]);

        $category = Categories::create($validatedData);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }
    public function show($id)
    {
        $category = Categories::findOrFail($id);

        return response()->json($category);
    }
    public function update(Request $request, $id)
    {
        $category = Categories::findOrFail($id);
        $category->name = $request->input('name');
        $category->pictureUrl = $request->input('pictureUrl');
        $category->save();
    
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }
    public function destroy($id)
{
    $category = Categories::findOrFail($id);

    $category->delete();

    return response()->json([
        'message' => 'Category deleted successfully'
    ], 204);

}


    
}
