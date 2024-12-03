<?php

namespace App\Http\Controllers;
use App\Models\Role;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function save_role(Request $request)
    {
        $request->validate([

            'name' => 'required',

        ]);
        $page = new Role;
        $page->name = $request->name;
        $page->save();
        return response()->json(['message' => 'Role Added Successfully.', 'data' => $page], 200);
    }


    public function update_role_by_id(Request $request, $id)
    {
        $request->validate([

            'name' => 'required',

        ]);

        $page = Role::find($id);
        if ($page) {
            $page->name = $request->name;
            $page->save(); // Use save() instead of update()

            return response()->json(['message' => 'Role Updated Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'Role Not Found.', 'data' => null], 404);
        }
    }




    public function get_all_role()
    {

       // Fetch all records from the Seo model
       $pages = Role::all();

       // Check if no records were found
       if ($pages->isEmpty()) {
           return response()->json(['error' => 'No Role data found'], 404);
       }

       // Return a JSON response with the data
       return response()->json([
           'message' => 'Role retrieved successfully.',
           'data' => $pages
       ], 200);
    }




    public function get_role_by_id($id)
    {
        $page = Role::find($id);

        // Check if the record exists
        if (!$page) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Return a JSON response with the data
        return response()->json([
            'message' => 'Role retrieved successfully.',
            'data' => $page
        ], 200);
    }



    public function delete_role_by_id($id)
    {

        $role = Role::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Role Deleted"];

        } else {
            return ["result" => "Role Wrong"];
        }
    }

}
