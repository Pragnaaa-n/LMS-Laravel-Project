<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function save_category(Request $request)
    {
        $request->validate([

            'exam_type_id' => 'required|exists:exam_types,id',
            'category_name' => 'required',

        ]);
        $page = new Category;
        $page->exam_type_id = $request->exam_type_id;
        $page->category_name = $request->category_name;
        $page->save();
        return response()->json(['message' => 'Category Added Successfully.', 'data' => $page], 200);
    }


    public function update_category_by_id(Request $request, $id)
    {
        $request->validate([

            'exam_type_id' => 'required|exists:exam_types,id',
            'category_name' => 'required',

        ]);

        $page = Category::find($id);
        if ($page) {
            $page->exam_type_id = $request->exam_type_id;
            $page->category_name = $request->category_name;
            $page->save();

            return response()->json(['message' => 'Category Updated Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'Category Not Found.', 'data' => null], 404);
        }
    }

    public function get_all_categories()
    {
        $categories = Category::with('examType')->get();
    
        if ($categories->isEmpty()) {
            return response()->json(['error' => 'No Category data found'], 404);
        }
    
        // Map through the data to append the full URL for the photo
        $categories = $categories->map(function ($category) {
            if ($category->examType) {
                $category->examType->photo = url($category->examType->photo);
            }
            return $category;
        });
    
        return response()->json([
            'message' => 'Categories retrieved successfully.',
            'data' => $categories
        ], 200);
    }
    

    public function get_category_by_id($id)
{
    $category = Category::with('examType')->find($id);

    if (!$category) {
        return response()->json(['error' => 'Category not found'], 404);
    }

    // Append the full URL for the photo
    if ($category->examType) {
        $category->examType->photo = url($category->examType->photo);
    }

    return response()->json([
        'message' => 'Category retrieved successfully.',
        'data' => $category
    ], 200);
}

public function getCategoriesByExamId($exam_type_id)
{
    // Fetch all categories where the `exam_type_id` matches
    $categories = Category::where('exam_type_id', $exam_type_id)->with('examType')->get();

    // Check if categories exist
    if ($categories->isEmpty()) {
        return response()->json(['error' => 'No categories found for the given exam type ID'], 404);
    }

    // Add full photo URL for each related exam type
    foreach ($categories as $category) {
        if ($category->examType) {
            $category->examType->photo = url($category->examType->photo);
        }
    }

    return response()->json([
        'message' => 'Categories retrieved successfully.',
        'data' => $categories
    ], 200);
}




    public function delete_category_by_id($id)
    {

        $role = Category::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Category Deleted"];

        } else {
            return ["result" => "Somthing went Wrong"];
        }
    }
}
