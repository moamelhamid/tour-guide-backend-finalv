<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Brick\Math\BigInteger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'dep_id' => 'required',
            'email' => 'required|email',
            'password' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $input = $request->all();
        $dep_id = BigInteger::of($input['dep_id']);
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'], // mast be backkkkkk to  bcrypt($input['password']),
            'dep_id' => $dep_id,
        ]);

        $success['user'] =  $user;
        return response()->json(['msg' => 'User register successfully.', 'data' => $success], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:6',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $credentials = $request->only(['name', 'password']);
        $user = User::where('name', $request->name)->where('password', $request->password)->first();
        $token = JWTAuth::fromUser($user);
        return response()->json(['success' => true, 'token' => $token], 200);
    }



    public function profile()
    {
        $success = Auth::user();
        return response()->json(['success' => true, 'data' => $success], 200);
    }



    public function refresh(Request $request)
    {
        $success = $this->respondWithToken(JWTAuth::refresh());
        return response()->json($success);
    }
    public function logout(Request $request)
    {
        $success = JWTAuth::invalidate($request->token);
        return response()->json(['success' => true, 'msg' => 'User logout successfully.'], 200);
    }
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => Auth::user()
        ];
    }

    public function updateProfile(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();

    // **Validate input**
    $validator = Validator::make($request->all(), [
        'email' => 'email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
        'home_location' => 'nullable|string',
        'phone_number' => 'nullable|string|min:10|max:15',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // **Update only the provided fields**
    if ($request->has('email')) {
        $user->email = $request->email;
    }
    if ($request->has('password')) {
        $user->password = $request->password;
    }
    if ($request->has('home_location')) {
        $user->home_location = $request->home_location;
    }
    if ($request->has('phone_number')) {
        $user->phone_number = $request->phone_number;
    }

    // **Save changes**
    $user->save();

    return response()->json([
        'success' => true,
        'msg' => 'Profile updated successfully.',
        'data' => $user
    ], 200);
}
}
