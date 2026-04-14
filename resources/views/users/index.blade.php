@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">User Management</h1>
            <p class="text-slate-500">Manage staff accounts and roles.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border mb-6">
    <form method="GET" action="{{ route('users.index') }}" class="grid md:grid-cols-3 gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search name or email..."
               class="rounded-lg border-slate-300">

        <select name="role" class="rounded-lg border-slate-300">
            <option value="">All Roles</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
            <option value="inventory" {{ request('role') == 'inventory' ? 'selected' : '' }}>Inventory Staff</option>
        </select>

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg">
                Filter
            </button>

            <a href="{{ route('users.index') }}"
               class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>
</div>

    <div class="bg-white rounded-2xl shadow p-6 border">
        <div class="overflow-x-auto">
            <table class="w-full border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Role</th>
                        <th class="p-3 text-left">Created</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">{{ $user->name }}</td>
                            <td class="p-3">{{ $user->email }}</td>
                            <td class="p-3">
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($user->role === 'admin') bg-purple-100 text-purple-700
                                    @elseif($user->role === 'cashier') bg-green-100 text-green-700
                                    @else bg-cyan-100 text-cyan-700
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="p-3">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Edit
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection