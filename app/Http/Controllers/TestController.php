<?php

namespace App\Http\Controllers;
use  App\Models\Test;
use Carbon\Carbon;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function save_test(Request $request)
    {
        $request->validate([

            'test_type_id' => 'required|exists:test_types,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'course_id' => 'required|exists:courses,id',
            'vimeo_link' => 'nullable',
            'youtube_link' => 'nullable',
            'time_picker_start_date' => 'nullable',
            'time_picker_end_date' => 'nullable',
            'date_picker_start_time' => 'nullable',
            'date_picker_end_time' => 'nullable',
            'description' => 'nullable',
            'status' => 'nullable',

        ]);
            $page = new Test;
            $page->test_type_id = $request->test_type_id;
            $page->exam_type_id = $request->exam_type_id;
            $page->course_id = $request->course_id;
            $page->vimeo_link = $request->vimeo_link;
            $page->youtube_link = $request->youtube_link;
            $page->time_picker_start_date = $request->time_picker_start_date;
            $page->time_picker_end_date = $request->time_picker_end_date;
            $page->date_picker_start_time = $request->date_picker_start_time;
            $page->date_picker_end_time = $request->date_picker_end_time;
            $page->description = $request->description;
            $page->status = $request->status;
            $page->save();

        return response()->json(['message' => 'Test Added Successfully.', 'data' => $page], 200);
    }


    public function update_test_by_id(Request $request, $id)
    {
        $request->validate([

            'test_type_id' => 'required|exists:test_types,id',
            'exam_type_id' => 'required|exists:exam_types,id',
            'course_id' => 'required|exists:courses,id',
            'vimeo_link' => 'nullable',
            'youtube_link' => 'nullable',
            'time_picker_start_date' => 'nullable',
            'time_picker_end_date' => 'nullable',
            'date_picker_start_time' => 'nullable',
            'date_picker_end_time' => 'nullable',
            'description' => 'nullable',
            'status' => 'nullable',

        ]);

        $page = Test::find($id);
        if ($page) {
            $page->test_type_id = $request->test_type_id;
            $page->exam_type_id = $request->exam_type_id;
            $page->course_id = $request->course_id;
            $page->vimeo_link = $request->vimeo_link;
            $page->youtube_link = $request->youtube_link;
            $page->time_picker_start_date = $request->time_picker_start_date;
            $page->time_picker_end_date = $request->time_picker_end_date;
            $page->date_picker_start_time = $request->date_picker_start_time;
            $page->date_picker_end_time = $request->date_picker_end_time;
            $page->description = $request->description;
            $page->status = $request->status;
            $page->save();

            return response()->json(['message' => 'Test Updated Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'Test Not Found.', 'data' => null], 404);
        }
    }


    public function delete_test_by_id($id)
    {

        $role = Test::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Test Deleted"];

        } else {
            return ["result" => "Somthing went Wrong"];
        }
    }


    public function get_all_tests()
    {
        $tests = Test::with(['testType', 'examType', 'course'])->get();
    
        if ($tests->isEmpty()) {
            return response()->json(['message' => 'No tests found.', 'data' => []], 200);
        }
    
        $baseUrl = url('/'); // Base URL for your application
    
        $tests->each(function ($test) use ($baseUrl) {
            // Append base URL to examType photo if it exists
            if (isset($test->examType) && $test->examType->photo) {
                $test->examType->photo = $this->generateFullUrl($test->examType->photo, $baseUrl);
            }
    
            // Append base URL to course banner_image if it exists
            if (isset($test->course) && $test->course->banner_image) {
                $test->course->banner_image = $this->generateFullUrl($test->course->banner_image, $baseUrl);
            }
        });
    
        return response()->json(['message' => 'Tests retrieved successfully.', 'data' => $tests], 200);
    }
    
    /**
     * Helper method to generate full URL for a given path
     */
    private function generateFullUrl($path, $baseUrl)
    {
        // Check if the path already contains the base URL
        if (strpos($path, $baseUrl) !== false) {
            return $path; // Already a full URL
        }
    
        // Generate full URL
        return $baseUrl . '/' . ltrim($path, '/');
    }
    

    public function get_test_by_id($id)
{
    $test = Test::with(['testType', 'examType', 'course'])->find($id);

    if (!$test) {
        return response()->json(['message' => 'Test not found.', 'data' => null], 404);
    }

    $baseUrl = url('/'); // Base URL

    // Transform the test data to include full URLs for images
    if (isset($test->examType) && isset($test->examType->photo)) {
        $test->examType->photo = $baseUrl . '/' . $test->examType->photo;
    }
    if (isset($test->course) && isset($test->course->banner_image)) {
        $test->course->banner_image = $baseUrl . '/' . $test->course->banner_image;
    }

    return response()->json(['message' => 'Test retrieved successfully.', 'data' => $test], 200);
}


    public function get_tests_by_test_type($test_type_id)
{
    // Retrieve tests that match the given test_type_id with their relationships
    $tests = Test::with(['testType', 'examType', 'course'])
        ->where('test_type_id', $test_type_id) // Ensure filtering by test_type_id
        ->get();

    // Check if tests are found
    if ($tests->isEmpty()) {
        return response()->json([
            'message' => 'No tests found for the given test_type_id.',
            'data' => []
        ], 404);
    }

    $baseUrl = url('/'); // Base URL for images

    // Add full URLs for related images
    $tests->transform(function ($test) use ($baseUrl) {
        // Update examType photo URL if available
        if (!empty($test->examType) && !empty($test->examType->photo)) {
            $test->examType->photo = $baseUrl . '/' . ltrim($test->examType->photo, '/');
        }

        // Update course banner_image URL if available
        if (!empty($test->course) && !empty($test->course->banner_image)) {
            $test->course->banner_image = $baseUrl . '/' . ltrim($test->course->banner_image, '/');
        }

        return $test;
    });

    // Return response with retrieved tests
    return response()->json([
        'message' => 'Tests retrieved successfully.',
        'data' => $tests
    ], 200);
}


public function updateStatusById($id)
{
    // Fetch the test by ID
    $test = Test::find($id);

    // Get the current time
    $currentTime = Carbon::now();

    // Get the start and end time of the test
    $startDate = Carbon::parse($test->date_picker_start_time . ' ' . $test->time_picker_start_date);
    $endDate = Carbon::parse($test->date_picker_end_time . ' ' . $test->time_picker_end_date);

    // Set status based on current time
    if ($currentTime->isBefore($startDate)) {
        $status = 0;  // Not started
    } elseif ($currentTime->isBetween($startDate, $endDate)) {
        $status = 1;  // In progress
    } else {
        $status = 0;  // Remain as 0 (not started) after the end date
    }

    // Update the test status
    $test->status = $status;
    $test->save();

    return response()->json([
        'message' => 'Test status updated successfully.',
        'data' => $test
    ]);
}


    
}
