<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// register by token
// class AuthController extends Controller
// {
//     public function register(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|min:3',
//             'email' => 'required|email|unique:users,email',
//             'password' => 'required|string|min:8',
//         ]);

//         $name = $request->name;
//         $email = $request->email;
//         $password = $request->password;

//         //customer details
//         $user = User::create([
//             'name' => $name,
//             'email' => Str::lower($email),
//             'password' => $password
//         ]);

//         $token = $user->createToken('web');

//         return ResponseHelper::success('تم إنشاء الحساب بنجاح', [
//             // 'name' => $name,
//             'user' => $user,
//             'token' => $token
//         ]);
//     }
//     public function login(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required|string',
//         ]);

//         $email = $request->email;
//         $password = $request->password;

//         $user = User::where('email', $email)->first();

//         if ($user && Hash::check($password, $user->password)) {
//             $token = $user->createToken('web')->plainTextToken;
//             return ResponseHelper::success('تم تسجيل الدخول بنجاح', [
//                 'user' => $user,
//                 'token' => $token
//             ]);
//         }
//         return ResponseHelper::failed("معلومات التسجيل خاطئة");
//     }
// }

class AuthController extends Controller
{
    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);
        $user = User::create($validated);
        Auth::login($user); // Auth from Facades
        $request->session()->regenerate();
        return ResponseHelper::success("تم تسجيل الحساب بنجاح");
    }

    function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        $rememberMe = $request->boolean('remember');
        if (! Auth::attempt($credentials, $rememberMe))
            throw ValidationException::withMessages(["email" => 'معلومات الإدخال خاطئة']);
        $request->session()->regenerate();
        return ResponseHelper::success("تم تسجيل الدخول بنجاح");
    }

    function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return ResponseHelper::success("تم تسجيل الخروج بنجاح");
    }
}
