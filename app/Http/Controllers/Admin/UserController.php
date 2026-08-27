<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->latest()->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function block(string $locale, User $user)
    {
        $user->update(['is_blocked' => ! $user->is_blocked]);

        return back()->with('status', __('User status updated.'));
    }
}
