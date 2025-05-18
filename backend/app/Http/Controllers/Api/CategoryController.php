<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function listCategory()
    {
        $data = Category::orderBy('name')->get();

        return response()->json([
            'status' => true,
            'message' => 'Data found',
            'data' => $data,
        ], 200);
    }

    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            $category = Category::create([
                'name' => $validated['name']
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully created a new category',
                'data' => $category->load(['user'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCategory(string $id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'status' => true,
                'message' => "Category found",
                'data' => $category,
                'audit' => $category->audits,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Category not found",
                'data' => $category
            ], 404);
        }
    }

    public function updateCategory(Request $request, string $id)
    {
        try {
            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $request->validate(
                [
                    'name' => 'required|string|unique:categories',
                ]   
            );

            $category->name = $request->name;
            $category->save();

            return response()->json([
                'status' => true,
                'message' => $request->name . ' category has been updated to '. $category->name,
                'data' => $category,
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update category',
                'error' => $error->getMessage(),
            ], 500);
        }
    }

    public function deleteCategory(string $id)
    {

        try {
            $category = Category::findOrFail($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $category->delete();
            return response()->json([
                'status' => true,
                'message' => $category->name . ' category has been deleted successfully',
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete category',
                'error' => $error->getMessage(),
            ], 500);
        }
    }
}
