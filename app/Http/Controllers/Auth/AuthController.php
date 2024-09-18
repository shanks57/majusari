<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Cari user berdasarkan email
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user) {
            // Cek apakah status user 0
            if ($user->status == 0) {
                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan. Silakan hubungi admin untuk mengaktifkan akun Anda.',
                ]);
            }

            // Cek apakah password kosong atau salah
            if (empty($user->password) || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                return back()->withErrors([
                    'password' => 'Password Anda tidak valid. Silakan hubungi admin untuk mengatur password Anda.',
                ]);
            }

            // Coba autentikasi
            if (Auth::attempt($credentials, $remember)) {
                /** @var \App\Models\User $user **/
                return redirect()->intended(route('dashboard-page'));
            }

            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah. Silakan coba lagi.',
            ]);
        } else {
            return back()->withErrors([
                'email' => 'Alamat email yang Anda masukkan tidak ditemukan. Silakan periksa dan coba lagi.',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        Auth::guard('web')->logout();
        return redirect(route('login'));
    }
}
