<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function save_sub_category(Request $request)
    {
        $request->validate([

            'exam_type_id' => 'required|exists:exam_types,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_name' => 'required',

        ]);
        $page = new SubCategory;
        $page->exam_type_id = $request->exam_type_id;
        $page->category_id = $request->category_id;
        $page->sub_category_name = $request->sub_category_name;
        $page->save();
        return response()->json(['message' => 'Sub Category Added Successfully.', 'data' => $page], 200);
    }


    public function update_sub_category_by_id(Request $request, $id)
    {
        $request->validate([

            'exam_type_id' => 'required|exists:exam_types,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_name' => 'required',

        ]);

        $page = SubCategory::find($id);
        if ($page) {
            $page->exam_type_id = $request->exam_type_id;
            $page->category_id = $request->category_id;
            $page->sub_category_name = $request->sub_category_name;
            $page->save();

            return response()->json(['message' => 'Sub Category Updated Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'Sub Category Not Found.', 'data' => null], 404);
        }
    }


    public function delete_sub_category_by_id($id)
    {

        $role = SubCategory::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Sub Category Deleted"];

        } else {
            return ["result" => "Somthing went Wrong"];
        }
    }

    public function get_all_sub_categories()
    {
    $sub_categories = SubCategory::with('examType', 'category')->get();

    if ($sub_categories->isEmpty()) {
        return response()->json(['error' => 'No Sub Category data found'], 404);
    }

    $sub_categories = $sub_categories->map(function ($sub_category) {
        if ($sub_category->examType) {
            $sub_category->examType->photo = url($sub_category->examType->photo);
        }
        return $sub_category;
    });

    return response()->json([
        'message' => 'Sub Categories retrieved successfully.',
        'data' => $sub_categories,
    ], 200);
    }

    public function get_sub_category_by_id($id)
    {
     $sub_category = SubCategory::with('examType', 'category')->find($id);

     if (!$sub_category) {
        return response()->json(['error' => 'Sub Category not found'], 404);
     }

     if ($sub_category->examType) {
        $sub_category->examType->photo = url($sub_category->examType->photo);
     }

     return response()->json([
        'message' => 'Sub Category retrieved successfully.',
        'data' => $sub_category,
     ], 200);
    }

public function getSubCategoriesByCategoryId($category_id)
{
    // Fetch all subcategories that belong to the given category_id
    $sub_categories = SubCategory::where('category_id', $category_id)
        ->with('examType', 'category')
        ->get();

    // Check if subcategories exist
    if ($sub_categories->isEmpty()) {
        return response()->json(['error' => 'No subcategories found for the given category ID'], 404);
    }

    // Add full photo URL for each related exam type
    foreach ($sub_categories as $sub_category) {
        if ($sub_category->examType) {
            $sub_category->examType->photo = url($sub_category->examType->photo);
        }
    }

    return response()->json([
        'message' => 'Subcategories retrieved successfully.',
        'data' => $sub_categories,
    ], 200);
}


}
