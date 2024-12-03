<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Cookie;
use Stripe;
use Session;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function __construct()
    {
        \Config::set('auth.defaults.guard','api-user');
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string', 
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $validator->validated();
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';

        if (!$token = auth()->attempt([$field => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }


    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|min:2|max:100',
        'role_id' => 'required|exists:roles,id',
        'email' => 'required|string|email|max:100|unique:users',
        'mobile_number' => 'required|string|unique:users|max:15',
        'profile_photo' => 'sometimes|nullable|file|image|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[A-Z]/',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&#]/',
        ],
    ], [
        'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one numeric value, and one special character.',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors()->toJson(), 400);
    }

    $data = $validator->validated();

    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'storage/photos/' . $fileName;

        $file->move(public_path('storage/photos'), $fileName);

        $data['profile_photo'] = url($filePath);
    }

    $data['password'] = bcrypt($request->password);

    $user = User::create($data);

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
    ], 201);
}


public function update(Request $request, $id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|min:2|max:100',
        'role_id' => 'sometimes|required|exists:roles,id',
        'email' => [
            'sometimes',
            'required',
            'string',
            'email',
            'max:100',
            Rule::unique('users')->ignore($user->id),
        ],
        'mobile_number' => [
            'sometimes',
            'required',
            'string',
            'max:15',
            Rule::unique('users')->ignore($user->id),
        ],
        'profile_photo' => 'sometimes|nullable|file|image|mimes:jpeg,png,jpg,gif,svg,webp,pdf',
        'password' => [
            'sometimes',
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/[A-Z]/',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&#]/',
        ],
    ], [
        'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one numeric value, and one special character.',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $data = $validator->validated();

    if ($request->hasFile('profile_photo')) {
        if ($user->profile_photo) {
            $oldPhotoPath = str_replace(url('/'), '', $user->profile_photo); 
            if (file_exists(public_path($oldPhotoPath))) {
                unlink(public_path($oldPhotoPath));
            }
        }

        $file = $request->file('profile_photo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'storage/photos/' . $fileName;

        if (!file_exists(public_path('storage/photos'))) {
            mkdir(public_path('storage/photos'), 0755, true);
        }

        $file->move(public_path('storage/photos'), $fileName);

        $data['profile_photo'] = url($filePath);
    }

    if (isset($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    }

    $user->update($data);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
    ], 200);
}


    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'User Successfully signed out']);
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' =>  strtotime(date('Y-m-d H:i:s', strtotime("+60 min"))),
                'user' => auth()->user()
        ]);
    }


    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function get_by_id($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Ensure the profile photo URL is a full HTTP URL
    if ($user->profile_photo && !str_contains($user->profile_photo, 'http')) {
        $user->profile_photo = url($user->profile_photo);
    }

    return response()->json($user, 200);
}

     
}
