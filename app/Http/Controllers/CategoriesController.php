<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::all();
        return response()->json($categories);
    }
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'image' => 'required|image|max:2048',
    ]);
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $file = $request->file('image');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('.\public\images', $filename);

    $category = new Categories();
    $category->name = $request->input('name');
    $category->image = $filename;
    $category->save();

    return response()->json(['success' => true, 'category' => $category]);
}
    public function show($id)
    {
        $category = Categories::findOrFail($id);

        return response()->json($category);
    }
   
    
    public function destroy($id)
{
    $category = Categories::findOrFail($id);

    $category->delete();

    return response()->json([
        'message' => 'Category deleted successfully'
    ], 204);

}
public function update(Request $request, $id)
{
    // validate input data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()]);
    }

    // find the category by ID
    $category = Categories::findOrFail($id);

    // make sure the name field is not null
    if ($request->has('name')) {
        $category->name = $request->input('name');
    }

    // check if a new image file has been uploaded
    if ($request->hasFile('image')) {
        // save the new image file and get its filename
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('public/images', $filename);

        // update the category record with the new image filename
        $category->image = $filename;

        // delete the old image file from storage
        Storage::delete('public/images/' . $category->image);
    }

    $category->save();

    // dd the updated category object before it's returned in the response
    dd($category);

    // return success response
    return response()->json(['success' => true, 'category' => $category]);
}


    
}
