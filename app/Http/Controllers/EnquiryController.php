<?php

namespace App\Http\Controllers;
use App\Models\Enquiry;

use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function save_Enquiry(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'email' => 'required',
            'mobile_number' => 'required',

        ]);
        $page = new Enquiry;
        $page->name = $request->name;
        $page->email = $request->email;
        $page->mobile_number = $request->mobile_number;
        $page->save();
        return response()->json(['message' => 'Enquiry Added Successfully.', 'data' => $page], 200);
    }



    public function get_all_Enquiry()
    {

       $pages = Enquiry::all();

       if ($pages->isEmpty()) {
           return response()->json(['error' => 'No Enquiry data found'], 404);
       }

       return response()->json([
           'message' => 'Enquiry retrieved successfully.',
           'data' => $pages
       ], 200);
    }




    public function get_Enquiry_by_id($id)
    {
        $page = Enquiry::find($id);

        if (!$page) {
            return response()->json(['error' => 'Enquiry not found'], 404);
        }

        return response()->json([
            'message' => 'Enquiry retrieved successfully.',
            'data' => $page
        ], 200);
    }



    public function delete_Enquiry_by_id($id)
    {

        $role = Enquiry::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Enquiry Deleted"];

        } else {
            return ["result" => "Enquiry Wrong"];
        }
    }
}
