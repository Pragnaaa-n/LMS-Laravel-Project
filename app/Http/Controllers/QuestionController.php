<?php

namespace App\Http\Controllers;
use App\Models\Question;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function save_question(Request $request)
    {
        $request->validate([

            'course_id' => 'required|exists:courses,id',
            'exam_id' => 'nullable|exists:exam_types,id',
            'test_type_id' => 'nullable|exists:test_types,id',
            'question' => 'required',
            'option1' => 'nullable',
            'option2' => 'nullable',
            'option3' => 'nullable',
            'option4' => 'nullable',
            'option5' => 'nullable',
            'option6' => 'nullable',
            'correct_answer' => 'nullable',
            'description' => 'nullable',

        ]);
            $page = new Question;
            $page->course_id = $request->course_id;
            $page->exam_id = $request->exam_id;
            $page->test_type_id = $request->test_type_id;
            $page->question = $request->question;
            $page->option1 = $request->option1;
            $page->option2 = $request->option2;
            $page->option3 = $request->option3;
            $page->option4 = $request->option4;
            $page->option5 = $request->option5;
            $page->option6 = $request->option6;
            $page->correct_answer = $request->correct_answer;
            $page->description = $request->description;
            $page->save();

        return response()->json(['message' => 'Question Added Successfully.', 'data' => $page], 200);
    }


    public function update_question_by_id(Request $request, $id)
    {
        $request->validate([

            'course_id' => 'required|exists:courses,id',
            'exam_id' => 'nullable|exists:exam_types,id',
            'test_type_id' => 'nullable|exists:test_types,id',
            'question' => 'required',
            'option1' => 'nullable',
            'option2' => 'nullable',
            'option3' => 'nullable',
            'option4' => 'nullable',
            'option5' => 'nullable',
            'option6' => 'nullable',
            'correct_answer' => 'nullable',
            'description' => 'nullable',

        ]);

        $page = Question::find($id);
        if ($page) {
            $page->course_id = $request->course_id;
            $page->exam_id = $request->exam_id;
            $page->test_type_id = $request->test_type_id;
            $page->question = $request->question;
            $page->option1 = $request->option1;
            $page->option2 = $request->option2;
            $page->option3 = $request->option3;
            $page->option4 = $request->option4;
            $page->option5 = $request->option5;
            $page->option6 = $request->option6;
            $page->correct_answer = $request->correct_answer;
            $page->description = $request->description;
            $page->save();

            return response()->json(['message' => 'Question Updated Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'Question Not Found.', 'data' => null], 404);
        }
    }


    public function delete_question_by_id($id)
    {

        $role = Question::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Question Deleted"];

        } else {
            return ["result" => "Somthing went Wrong"];
        }
    }


    public function get_all_questions()
{
    $questions = Question::with(['testType', 'examType', 'course'])->get();

    if ($questions->isEmpty()) {
        return response()->json(['message' => 'No Question found.', 'data' => []], 200);
    }

    $baseUrl = url('/'); // Base URL

    // Transform the questions data to include full URLs for images
    $questions->transform(function ($question) use ($baseUrl) {
        if (isset($question->examType) && isset($question->examType->photo)) {
            $question->examType->photo = $baseUrl . '/' . $question->examType->photo;
        }
        if (isset($question->course) && isset($question->course->banner_image)) {
            $question->course->banner_image = $baseUrl . '/' . $question->course->banner_image;
        }
        return $question;
    });

    return response()->json(['message' => 'Questions retrieved successfully.', 'data' => $questions], 200);
}

public function get_question_by_id($id)
{
    $question = Question::with(['testType', 'examType', 'course'])->find($id);

    if (!$question) {
        return response()->json(['message' => 'Question not found.', 'data' => null], 404);
    }

    $baseUrl = url('/'); // Base URL

    // Transform the question data to include full URLs for images
    if (isset($question->examType) && isset($question->examType->photo)) {
        $question->examType->photo = $baseUrl . '/' . $question->examType->photo;
    }
    if (isset($question->course) && isset($question->course->banner_image)) {
        $question->course->banner_image = $baseUrl . '/' . $question->course->banner_image;
    }

    return response()->json(['message' => 'Question retrieved successfully.', 'data' => $question], 200);
}

   public function get_questions_by_test_type_id($test_type_id)
   {
    $questions = Question::with(['testType', 'examType', 'course'])
        ->where('test_type_id', $test_type_id)
        ->get();

    if ($questions->isEmpty()) {
        return response()->json(['message' => 'No questions found for the given test type.', 'data' => []], 200);
    }

    $baseUrl = url('/'); // Base URL

    // Transform the questions data to include full URLs for images
    $questions->transform(function ($question) use ($baseUrl) {
        if (isset($question->examType) && isset($question->examType->photo)) {
            $question->examType->photo = $baseUrl . '/' . $question->examType->photo;
        }
        if (isset($question->course) && isset($question->course->banner_image)) {
            $question->course->banner_image = $baseUrl . '/' . $question->course->banner_image;
        }
        return $question;
    });

    return response()->json(['message' => 'Questions retrieved successfully.', 'data' => $questions], 200);
}


}
