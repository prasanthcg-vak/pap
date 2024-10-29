<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'category_description' => 'required|string|max:255',
                'is_active' => 'boolean',
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : 0;

            Category::create($data);

            return response()->json(['success' => 'Category created successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the category'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'category_name' => 'required|string|max:255',
                'category_description' => 'required|string|max:255',
                'is_active' => 'boolean',
            ]);

            $data = $request->all();
            $data['is_active'] = $request->has('is_active') ? $request->input('is_active') : 0;

            $category = Category::findOrFail($id);
            $category->update($data);

            return response()->json(['success' => 'Category updated successfully']);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the category'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the category'], 500);
        }
    }
}
