<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function save_permission(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'permission_title' => 'nullable',
            'description' => 'nullable',

        ]);
        $page = new Permission;
        $page->name = $request->name;
        $page->permission_title = $request->permission_title;
        $page->description = $request->description;
        $page->save();
        return response()->json(['message' => 'Permission Added Successfully.', 'data' => $page], 200);
    }



    public function get_all_permission()
    {

       // Fetch all records from the Seo model
       $pages = Permission::all();

       // Check if no records were found
       if ($pages->isEmpty()) {
           return response()->json(['error' => 'No Permission data found'], 404);
       }

       // Return a JSON response with the data
       return response()->json([
           'message' => 'Permission retrieved successfully.',
           'data' => $pages
       ], 200);
    }




    public function get_permission_by_id($id)
    {
        $page = Permission::find($id);

        // Check if the record exists
        if (!$page) {
            return response()->json(['error' => 'Permission not found'], 404);
        }

        // Return a JSON response with the data
        return response()->json([
            'message' => 'Permission retrieved successfully.',
            'data' => $page
        ], 200);
    }



    public function delete_permission_by_id($id)
    {

        $role = Permission::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Permission Deleted"];

        } else {
            return ["result" => "Permission Wrong"];
        }
    }
}
