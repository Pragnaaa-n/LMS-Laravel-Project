<?php

namespace App\Http\Controllers;
use App\Models\Course;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function save_course(Request $request)
    {
        $request->validate([

            'exam_type_id' => 'required|exists:exam_types,id',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'course_name' => 'required',
            'description' => 'required',
            'banner_image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
            'video_link' => 'required',
            'vimeo_video' => 'required',
        ]);

        $page = new Course;

        $page->exam_type_id = $request->exam_type_id;
        $page->category_id = $request->category_id;
        $page->sub_category_id = $request->sub_category_id;
        $page->course_name = $request->course_name;
        $page->description = $request->description;
        $page->banner_image = $request->banner_image;
        $page->video_link = $request->video_link;
        $page->vimeo_video = $request->vimeo_video;

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = public_path('storage/banner_image');
            $file->move($filePath, $fileName);
            $page->banner_image = 'storage/banner_image/'.$fileName; 
        }

        $page->save();

        return response()->json([
            'message' => 'Course Added Successfully.',
            'data' => $page
        ], 200);
    }


    public function update_course_id(Request $request, $id)
{
    $request->validate([
        'exam_type_id' => 'required|exists:exam_types,id',
        'category_id' => 'required|exists:categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'course_name' => 'required',
        'description' => 'required',
        'banner_image' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
        'video_link' => 'required',
        'vimeo_video' => 'required',
    ]);

    $page = Course::find($id);

    if (!$page) {
        return response()->json(['message' => 'Course not found.'], 404);
    }

    $page->exam_type_id = $request->exam_type_id;
    $page->category_id = $request->category_id;
    $page->sub_category_id = $request->sub_category_id;
    $page->course_name = $request->course_name;
    $page->description = $request->description;
    $page->video_link = $request->video_link;
    $page->vimeo_video = $request->vimeo_video;

    if ($request->hasFile('banner_image')) {
        $oldImage = public_path($page->banner_image);

        if ($page->banner_image && file_exists($oldImage)) {
            unlink($oldImage);
        }

        $file = $request->file('banner_image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = public_path('storage/banner_image');

        if (!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        $file->move($filePath, $fileName);

        $page->banner_image = 'storage/banner_image/' . $fileName;
    }

    $page->save();

    return response()->json([
        'message' => 'Course Updated Successfully.',
        'data' => $page
    ], 200);
}


    public function delete_course_by_id($id)
    {
    
        $page = Course::find($id);
    
        if (!$page) {
            return response()->json(['error' => 'Course not found'], 404);
        }
    
        if ($page->banner_image) {
            $featuredImagePath = public_path($page->banner_image);
    
            if (file_exists($featuredImagePath)) {
                unlink($featuredImagePath);
            }
        }
    
       $result = $page->delete();
    
        if ($result) {
            return response()->json(['message' => 'Course Deleted'], 200);
        } else {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function get_all_courses()
{
    $pages = Course::with(['examType', 'category', 'subCategory'])->get();

    if ($pages->isEmpty()) {
        return response()->json(['error' => 'No Course data found'], 404);
    }

    $pages = $pages->map(function ($page) {
        $page->banner_image = $page->banner_image ? asset($page->banner_image) : null;
        return $page;
    });

    return response()->json([
        'message' => 'Courses retrieved successfully.',
        'data' => $pages
    ], 200);
}


public function get_courses_by_id($id)
{
    $page = Course::with(['examType', 'category', 'subCategory'])->find($id);

    if (!$page) {
        return response()->json(['error' => 'Course not found'], 404);
    }

    $page->banner_image = $page->banner_image ? asset($page->banner_image) : null;

    return response()->json([
        'message' => 'Course retrieved successfully.',
        'data' => $page
    ], 200);
}

public function get_courses_by_exam_id($exam_type_id)
{
    $courses = Course::with(['category', 'subCategory'])
        ->where('exam_type_id', $exam_type_id)
        ->get();

    if ($courses->isEmpty()) {
        return response()->json(['error' => 'No courses found for the specified exam type'], 404);
    }

    $courses = $courses->map(function ($course) {
        $course->banner_image = $course->banner_image ? asset($course->banner_image) : null;
        return $course;
    });

    return response()->json([
        'message' => 'Courses retrieved successfully.',
        'data' => $courses
    ], 200);
}


}
