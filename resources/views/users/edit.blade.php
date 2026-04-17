@extends('layouts.app')

@section('content')

<style>
.useredit-root {
    max-width: 640px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.useredit-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.useredit-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.useredit-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 18px;
}

.useredit-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.useredit-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.useredit-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #bbb;
}

.useredit-input,
.useredit-select {
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

.useredit-input:focus,
.useredit-select:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.useredit-input[disabled] {
    background: #f5f3ec;
    color: #777;
    cursor: not-allowed;
}

.useredit-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 6px;
}

.useredit-btn {
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

.useredit-btn:hover {
    opacity: .85;
}

.useredit-btn-soft {
    background: transparent;
    color: #888;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.useredit-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}
</style>

<div class="useredit-root">

    <div>
        <div class="useredit-header-title">Edit User Role</div>
        <div class="useredit-header-sub">Update access level for {{ $user->name }}</div>
    </div>

    <div class="useredit-card">
        <form action="{{ route('users.update', $user) }}" method="POST" class="useredit-form">
            @csrf
            @method('PUT')

            <div class="useredit-group">
                <label class="useredit-label">Name</label>
                <input type="text"
                       value="{{ $user->name }}"
                       class="useredit-input"
                       disabled>
            </div>

            <div class="useredit-group">
                <label class="useredit-label">Email</label>
                <input type="text"
                       value="{{ $user->email }}"
                       class="useredit-input"
                       disabled>
            </div>

            <div class="useredit-group">
                <label class="useredit-label">Role</label>
                <select name="role" class="useredit-select" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="inventory" {{ $user->role === 'inventory' ? 'selected' : '' }}>Inventory Staff</option>
                </select>
            </div>

            <div class="useredit-actions">
                <a href="{{ route('users.index') }}" class="useredit-btn-soft">
                    Cancel
                </a>

                <button type="submit" class="useredit-btn">
                    Update Role
                </button>
            </div>

        </form>
    </div>

</div>

@endsection