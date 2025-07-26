<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Gelen username ve password'ü al
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Kullanıcıyı username ile bul
        $user = User::where('name', $request->username)->first();

        if (!$user) {
            return response()->json(['message' => 'Kullanıcı bulunamadı'], 404);
        }

        // Şifre kontrolü
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Şifre yanlış'], 401);
        }

        // Başarılı ise token oluştur (örneğin basit token, ya da JWT)
        $token = $user->createToken('mobile-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Başarılı giriş',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'token' => $token,
        ]);
    }
}
