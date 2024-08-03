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
        // dd($request->all());
        if (Auth::attempt($credentials, $remember)) {
            /** @var \App\Models\User $user **/
            return redirect()->intended(route('dashboard-page'));
        }

        return back()->withErrors([
            session()->flash('email', 'eror lo')
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
