<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
   public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user, 'access_token' => $token]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()
                ->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        // $request->session()->regenerate();
        $user = User::where('email', $request['email'])->firstOrFail();
        // $request->user()->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['success' => true, 'message' => 'Hi ' . $user->name . ', Selamat Datang di To Do List', 'access_token' => $token, 'email' => $user->email]);
    }
}
