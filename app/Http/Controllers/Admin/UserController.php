<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('businesses')->orderBy('role')->orderBy('name')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function toggle(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot suspend your own account.']);
        }

        $user->update(['status' => $user->status === 'active' ? 'suspended' : 'active']);

        return back()->with('status', $user->name.' is now '.$user->status.'.');
    }
}
