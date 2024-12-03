<?php

namespace App\Http\Controllers;
use App\Models\ExamType;

use Illuminate\Http\Request;

class ExamTypeController extends Controller
{
    public function save_exam_type(Request $request)
    {
        $request->validate([

            'exam_name' => 'required',
            'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
        ]);

        $page = new ExamType;

        $page->exam_name = $request->exam_name;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = public_path('storage/photo');
            $file->move($filePath, $fileName);
            $page->photo = 'storage/photo/'.$fileName; 
        }

        $page->save();

        return response()->json([
            'message' => 'Exam Type Added Successfully.',
            'data' => $page
        ], 200);
    }


    public function update_exam_type_by_id(Request $request, $id)
    {
        $request->validate([
            'exam_name' => 'required',
            'photo' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
        ]);
    
        $page = ExamType::find($id);
    
        if (!$page) {
            return response()->json(['message' => 'Exam Type not found.'], 404);
        }
    
        // Update the exam name
        $page->exam_name = $request->exam_name;
    
        // Handle photo upload and replacement
        if ($request->hasFile('photo')) {
            $oldPhotoPath = public_path($page->photo);
    
            // Delete the old photo if it exists
            if ($page->photo && file_exists($oldPhotoPath)) {
                unlink($oldPhotoPath);
            }
    
            // Upload the new photo
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('storage/photo');
    
            // Ensure the directory exists
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
    
            $file->move($uploadPath, $fileName);
    
            // Update the photo path in the database
            $page->photo = 'storage/photo/' . $fileName;
        }
    
        // Save changes to the database
        $page->save();
    
        return response()->json([
            'message' => 'Exam Type Updated Successfully.',
            'data' => $page
        ], 200);
    }
    



    public function get_all_exam_type()
    {
        $pages = ExamType::all();

        if ($pages->isEmpty()) {
            return response()->json(['error' => 'No Exam type data found'], 404);
        }

        $pages = $pages->map(function ($page) {
            $page->photo = $page->photo ? asset($page->photo) : null;

            return $page;
        });

        return response()->json([
            'message' => 'Exam Type retrieved successfully.',
            'data' => $pages
        ], 200);
    }



    public function get_exam_type_by_id($id)
    {
        $page = ExamType::find($id);

        if (!$page) {
            return response()->json(['error' => 'Exam Type not found'], 404);
        }

        $page->photo = $page->photo ? asset($page->photo) : null;

        return response()->json([
            'message' => 'Exam Type retrieved successfully.',
            'data' => $page
        ], 200);
    }


    public function delete_exam_type_by_id($id)
{

    $page = ExamType::find($id);

    if (!$page) {
        return response()->json(['error' => 'Exam Type not found'], 404);
    }

    if ($page->photo) {
        $featuredImagePath = public_path($page->photo);

        if (file_exists($featuredImagePath)) {
            unlink($featuredImagePath);
        }
    }

   $result = $page->delete();

    if ($result) {
        return response()->json(['message' => 'Exam Type Deleted'], 200);
    } else {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}
}
