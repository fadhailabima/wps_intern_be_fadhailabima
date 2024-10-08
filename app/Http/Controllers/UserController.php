<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function okResponse($message, $data = [])
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], 200);
    }

    protected function unautheticatedResponse($message)
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $loginData['username'])->first();

        if (!$user || !Hash::check($loginData['password'], $user->password)) {
            return $this->unautheticatedResponse('Kombinasi username dan password tidak valid.');
        }

        $token = $user->createToken('authToken')->plainTextToken;
        $userData = array_merge($user->toArray(), ['token' => $token, 'nama_role' => $user->role->nama_role]);

        return $this->okResponse("Login Berhasil", ['user' => $userData]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout berhasil']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getCurrentUser(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userData = array_merge($user->toArray(), [
            'nama_role' => $user->role->nama_role,
            'nama_divisi' => $user->divisi ? $user->divisi->nama_divisi : null
        ]);

        return response()->json(['user' => $userData], 200);
    }

}
