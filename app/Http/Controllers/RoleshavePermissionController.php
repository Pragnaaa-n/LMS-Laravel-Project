<?php

namespace App\Http\Controllers;
use App\Models\Roleshavepermission;

use Illuminate\Http\Request;

class RoleshavePermissionController extends Controller
{
    public function assign_role_have_permission(Request $request)
    {
        $request->validate([

            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',

        ]);
        $page = new Roleshavepermission;
        $page->role_id = $request->role_id;
        $page->permission_id = $request->permission_id;
        $page->save();
        return response()->json(['message' => 'Role assigned permission Successfully.', 'data' => $page], 200);
    }


    public function update_role_have_permission_by_id(Request $request, $id)
    {
        $request->validate([

            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',

        ]);

        $page = Roleshavepermission::find($id);
        if ($page) {
            $page->role_id = $request->role_id;
        $page->permission_id = $request->permission_id;
            $page->save(); // Use save() instead of update()

            return response()->json(['message' => 'Role Updated permission  Successfully.', 'detail' => $page], 200);
        } else {
            return response()->json(['message' => 'Role Not Found.', 'data' => null], 404);
        }
    }




    public function get_all_role_have_permission()
    {

       // Fetch all records from the Seo model
       $pages = Roleshavepermission::all();

       // Check if no records were found
       if ($pages->isEmpty()) {
           return response()->json(['error' => 'No Roles have Permission data found'], 404);
       }

       // Return a JSON response with the data
       return response()->json([
           'message' => 'Roles have Permission retrieved  successfully.',
           'data' => $pages
       ], 200);
    }




    public function get_role_have_permission_by_id($id)
    {
        $page = Roleshavepermission::find($id);

        // Check if the record exists
        if (!$page) {
            return response()->json(['error' => 'Roles have Permission data not found'], 404);
        }

        // Return a JSON response with the data
        return response()->json([
            'message' => 'Role have Permission retrieved successfully.',
            'data' => $page
        ], 200);
    }



    public function delete_role_have_permission_by_id($id)
    {

        $role = Roleshavepermission::find($id);
        $result = $role->delete();
        if ($result) {
            return ["result" => "Roles have Permission Deleted Successfully"];

        } else {
            return ["result" => "Roles have Permission Wrong"];
        }
    }
}
