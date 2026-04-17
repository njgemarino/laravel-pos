<?php

namespace App\Http\Controllers;

use App\Traits\LogsActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use LogsActivity;
   public function index(Request $request)
{
    $query = User::query();

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->role) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->get();

    return view('users.index', compact('users'));
}

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,cashier,inventory',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User role updated successfully.');
        $this->logActivity(
    'Update User',
    'Users',
    'Updated role for ' . $user->name . ' to ' . $request->role
);
    }
    

    public function destroy(User $user)
    {
        if (Auth::id() == $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        $this->logActivity(
    'Delete User',
    'Users',
    'Deleted user: ' . $user->name
);
    }
}