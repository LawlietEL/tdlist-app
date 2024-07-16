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
    // Validasi input dari request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    // Jika validasi gagal, kembalikan respons dengan pesan kesalahan
    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()->toJson(),
        ], 422);
    }

    // Buat entri pengguna baru dalam database
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // Buat token akses untuk pengguna
    $token = $user->createToken('auth_token')->plainTextToken;

    // Kembalikan respons JSON dengan data pengguna dan token akses
    return response()->json([
        'success' => true,
        'message' => 'Akun berhasil dibuat, silahkan login.', 
        'data' => $user,
        'access_token' => $token,
    ], 200); // 200 OK, registrasi berhasil
}

    

    public function login(Request $request)
{
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()
            ->json(['success' => false, 'message' => '"Incorrect email or password, please check again."'], 401);
    }
    
    // $request->session()->regenerate();
    $user = User::where('email', $request['email'])->firstOrFail();

    // $request->user()->tokens()->delete();
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()
        ->json(['success' => true, 'message' => 'Hi ' . $user->name . ', welcome to To DO List', 
        'access_token' => $token, 
        'name' => $user->name,
        'email' => $user->email,
        'users_id' => $user->id]);
}


    public function user(Request $request)
    {
        try {
            $data = $request->user();
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Data tersedia',
            ];
            return response()->json($response, 200);
        } catch (Exception $th) {
            $response = [
                'success' => false,
                'message' => $th,
            ];
            return response()->json($response, 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        //auth()->user()->tokens()->delete();

        return response()
            ->json(['success' => true,
                'message' => 'You have been signed out.',
            ]);
    }

    public function change_password(Request $request)
    {
        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords matches
            return response()->json(['success' => false,
                'message' => 'Your current password does not matches with the password.', 401]);
        }

        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            return response()->json(['success' => false,
                'message' => 'New Password cannot be same as your current password.', 401]);
        }
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        // auth()->user()->tokens()->delete();

        return response()
            ->json(['success' => true,
                'message' => 'Your password has been changed.',
            ]);

    }

    public function search(Request $request)
    {
        try {
            $data = User::where('name', 'LIKE', '%' . $request->search . '%')->orWhere('email', 'LIKE', '%' . $request->search . '%')->get();
            $response = [
                'success' => true,
                'data' => $data,
                'message' => 'Data available',
            ];
            return response()->json($response, 200);
        } catch (Exception $th) {
            $response = [
                'success' => false,
                'message' => $th,
            ];
            return response()->json($response, 500);
        }
    }
}
