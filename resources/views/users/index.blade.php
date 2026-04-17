@extends('layouts.app')

@section('content')

<style>
.users-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.users-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.users-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.users-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
}

.users-filter {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
}

@media (min-width: 768px) {
    .users-filter {
        grid-template-columns: 1.5fr 1fr auto;
    }
}

.users-input,
.users-select {
    width: 100%;
    background: #faf9f5;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 10px;
    font-size: 12px;
    color: #0f0f0f;
    outline: none;
    font-family: inherit;
    transition: border-color .15s, background .15s;
}

.users-input:focus,
.users-select:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.users-btn {
    background: #0f0f0f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity .15s;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.users-btn:hover {
    opacity: .85;
}

.users-btn-soft {
    background: transparent;
    color: #888;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12px;
    cursor: pointer;
    font-family: inherit;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.users-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}

.users-btn-danger {
    background: transparent;
    color: #dc2626;
    border: 0.5px solid #fca5a5;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 11px;
    cursor: pointer;
    font-family: inherit;
    transition: background .15s, opacity .15s;
}

.users-btn-danger:hover {
    background: #fef2f2;
}

.users-btn-row {
    display: flex;
    gap: 6px;
}

.users-table-wrap {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead {
    background: #faf9f5;
}

.users-table th {
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #888;
    text-align: left;
    white-space: nowrap;
}

.users-table td {
    padding: 12px;
    font-size: 12px;
    color: #1a1a1a;
    border-top: 0.5px solid #f0ece0;
    vertical-align: middle;
}

.users-row:hover {
    background: #fcfbf8;
}

.users-muted {
    color: #999;
    font-size: 12px;
}

.role-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 10px;
    border: 0.5px solid transparent;
}

.role-admin {
    background: #f5f3ff;
    color: #7c3aed;
    border-color: #ddd6fe;
}

.role-cashier {
    background: #ecfdf5;
    color: #059669;
    border-color: #a7f3d0;
}

.role-inventory {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
}

.empty-box {
    text-align: center;
    padding: 24px;
    color: #999;
    font-size: 12px;
}
</style>

<div class="users-root">

    <div>
        <div class="users-header-title">User Management</div>
        <div class="users-header-sub">Manage staff accounts and roles</div>
    </div>

    <div class="users-card">
        <form method="GET" action="{{ route('users.index') }}" class="users-filter">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search name or email..."
                   class="users-input">

            <select name="role" class="users-select">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="cashier" {{ request('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                <option value="inventory" {{ request('role') == 'inventory' ? 'selected' : '' }}>Inventory Staff</option>
            </select>

            <div class="users-btn-row">
                <button type="submit" class="users-btn">Apply</button>
                <a href="{{ route('users.index') }}" class="users-btn-soft">Reset</a>
            </div>

        </form>
    </div>

    <div class="users-card">
        <div class="users-table-wrap">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="users-row">
                            <td>{{ $user->name }}</td>
                            <td class="users-muted">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="role-pill role-admin">Admin</span>
                                @elseif($user->role === 'cashier')
                                    <span class="role-pill role-cashier">Cashier</span>
                                @else
                                    <span class="role-pill role-inventory">Inventory</span>
                                @endif
                            </td>
                            <td class="users-muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td style="text-align:center;">
                                <div class="users-btn-row" style="justify-content:center;">
                                    <a href="{{ route('users.edit', $user) }}" class="users-btn-soft">
                                        Edit
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="users-btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-box">No users found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection