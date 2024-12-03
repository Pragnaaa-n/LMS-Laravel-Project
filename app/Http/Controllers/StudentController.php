<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    
    public function __construct()
    {
        \Config::set('auth.defaults.guard','student-api');
    }

    public function login(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|string', // 'login' field for email or mobile number
            'password' => 'required|string|min:6', // 'password' field
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Get validated input data
        $credentials = $validator->validated();

        // Check if the 'login' is an email or a mobile number
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        // Attempt to authenticate using either email or mobile number
        if (!$token = auth()->attempt([$field => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the generated token
        return $this->createNewToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:100',
            'mobile' => 'required|string|min:2|max:15',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'email' => 'required|string|email|max:100|unique:students,email',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $profileImagePath = null;

        // Handle profile image upload if provided
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '_' . $profileImage->getClientOriginalName();
            $profileImage->move(public_path('profile_images'), $imageName);
            $profileImagePath = 'profile_images/' . $imageName;
        }

        // Create the student record
        $user = Student::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'profile_image' => $profileImagePath, // Set the profile image path or null
            ]
        ));

        return response()->json([
            'message' => 'Student registered successfully',
            'student' => $user,
        ], 201);
    }

    public function index()
    {
        // Retrieve all students
        $students = Student::all();

        // Append the full profile image URL to each student
        $students->map(function ($student) {
            if ($student->profile_image) {
                $student->profile_image_url = url($student->profile_image); // Generate full URL
            } else {
                $student->profile_image_url = null; // Handle null image case
            }
            return $student;
        });

        return response()->json([
            'message' => 'Students retrieved successfully',
            'students' => $students,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|min:2|max:100',
            'mobile' => 'nullable|string|min:2|max:15',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:1024',
            'email' => 'nullable|string|email|max:100|unique:students,email,' . $id,
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'zip' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $profileImagePath = $student->profile_image;

        // Handle profile image update if provided
        if ($request->hasFile('profile_image')) {
            // Delete the old image if it exists
            if ($profileImagePath && file_exists(public_path($profileImagePath))) {
                unlink(public_path($profileImagePath));
            }

            $profileImage = $request->file('profile_image');
            $imageName = time() . '_' . $profileImage->getClientOriginalName();
            $profileImage->move(public_path('profile_images'), $imageName);
            $profileImagePath = 'profile_images/' . $imageName;
        }

        // Update the student record
        $student->update(array_merge(
            $validator->validated(),
            [
                'profile_image' => $profileImagePath, // Set updated profile image path or keep old
                'password' => $request->password ? bcrypt($request->password) : $student->password, // Update password if provided
            ]
        ));

        return response()->json([
            'message' => 'Student updated successfully',
            'student' => $student,
        ], 200);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Student Successfully signed out']);
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' =>  strtotime(date('Y-m-d H:i:s', strtotime("+60 min"))),
                'student' => auth()->user()
        ]);
    }

    public function refresh(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $newToken = JWTAuth::refresh($token);
            return response()->json(['token' => $newToken], 200);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expired, please login again.'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not refresh token.'], 500);
        }
    }

    public function getStudentById($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Append full profile image URL
        if ($student->profile_image) {
            $student->profile_image_url = url($student->profile_image);
        } else {
            $student->profile_image_url = null;
        }

        return response()->json([
            'message' => 'Student retrieved successfully',
            'student' => $student,
        ], 200);
    }

    public function deleteStudentById($id)
    {
        // Find the student record by ID
        $student = Student::find($id);

        // Check if the student exists
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Delete the profile image if it exists
        if ($student->profile_image && file_exists(public_path($student->profile_image))) {
            unlink(public_path($student->profile_image));
        }

        // Delete the student record
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully'], 200);
    }
}
