<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $attempt = Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password'], 'status' => 'active'],
            $request->boolean('remember')
        );

        if (! $attempt) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Wrong email or password, or the account is suspended.']);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->homeFor(Auth::user()));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($data + ['role' => 'owner', 'status' => 'active']);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('owner.dashboard')->with('status', 'Welcome to LOOKal. Add your first listing.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function homeFor(User $user): string
    {
        return $user->isAdmin() ? route('admin.dashboard') : route('owner.dashboard');
    }
}
