@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow p-8 border">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Edit User Role</h1>
        <p class="text-slate-500 mb-6">Update role for {{ $user->name }}</p>

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                <input type="text" value="{{ $user->name }}" disabled
                       class="w-full rounded-lg border-slate-300 bg-slate-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="text" value="{{ $user->email }}" disabled
                       class="w-full rounded-lg border-slate-300 bg-slate-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                <select name="role" class="w-full rounded-lg border-slate-300" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="inventory" {{ $user->role === 'inventory' ? 'selected' : '' }}>Inventory Staff</option>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2 rounded-lg">
                    Update Role
                </button>

                <a href="{{ route('users.index') }}"
                   class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-5 py-2 rounded-lg">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection