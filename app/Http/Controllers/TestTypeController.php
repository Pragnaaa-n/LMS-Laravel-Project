<?php

namespace App\Http\Controllers;
use App\Models\TestType;

use Illuminate\Http\Request;

class TestTypeController extends Controller
{
    public function save_test_type(Request $request)
    {
        $request->validate([

            'test_type_name' => 'required',

        ]);
        $page = new TestType;
        $page->test_type_name = $request->test_type_name;
        $page->save();
        return response()->json(['message' => 'TestType Added Successfully.', 'data' => $page], 200);
    }


    public function update_test_type_by_id(Request $request, $id)
    {
        $request->validate([

            'test_type_name' => 'required',

        ]);

        $page = TestType::find($id);
        if ($page) {
            $page->test_type_name = $request->test_type_name;
            $page->save(); 

            return response()->json(['message' => 'TestType Updated Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'TestType Not Found.', 'data' => null], 404);
        }
    }


    public function get_all_test_type()
    {

       $pages = TestType::all();

       if ($pages->isEmpty()) {
           return response()->json(['error' => 'No TestType data found'], 404);
       }

       return response()->json([
           'message' => 'TestType retrieved successfully.',
           'data' => $pages
       ], 200);
    }


    public function get_test_type_by_id($id)
    {
        $page = TestType::find($id);

        if (!$page) {
            return response()->json(['error' => 'TestType not found'], 404);
        }


        return response()->json([
            'message' => 'TestType retrieved successfully.',
            'data' => $page
        ], 200);
    }


    public function delete_test_type_by_id($id)
    {

        $role = TestType::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "TestType Deleted"];

        } else {
            return ["result" => "TestType Wrong"];
        }
    }
}
