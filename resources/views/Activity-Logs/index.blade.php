@extends('layouts.app')

@section('content')

<style>
.al-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.al-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.al-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.al-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
}

.al-filter {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
}

@media (min-width: 768px) {
    .al-filter {
        grid-template-columns: 1.5fr 1fr 1fr auto;
    }
}

.al-input,
.al-select {
    width: 100%;
    background: #faf9f5;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 10px;
    font-size: 12px;
    color: #0f0f0f;
    outline: none;
    font-family: inherit;
}

.al-input:focus,
.al-select:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.al-btn {
    background: #0f0f0f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
}

.al-btn:hover {
    opacity: .85;
}

.al-btn-soft {
    background: transparent;
    color: #888;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12px;
    text-decoration: none;
}

.al-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}

.al-btn-row {
    display: flex;
    gap: 6px;
}

.al-table-wrap {
    overflow-x: auto;
}

.al-table {
    width: 100%;
    border-collapse: collapse;
}

.al-table thead {
    background: #faf9f5;
}

.al-table th {
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #888;
    text-align: left;
    white-space: nowrap;
}

.al-table td {
    padding: 12px;
    font-size: 12px;
    color: #1a1a1a;
    border-top: 0.5px solid #f0ece0;
    vertical-align: middle;
}

.al-row:hover {
    background: #fcfbf8;
}

.al-muted {
    color: #999;
    font-size: 12px;
}

.al-pill {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 10px;
    background: #f5f3ff;
    color: #7c3aed;
    border: 0.5px solid #ddd6fe;
}

.empty-box {
    text-align: center;
    padding: 24px;
    color: #999;
    font-size: 12px;
}
</style>

<div class="al-root">

    <div>
        <div class="al-header-title">Activity Logs</div>
        <div class="al-header-sub">Track user actions across the system</div>
    </div>

    <div class="al-card">
        <form method="GET" action="{{ route('activity-logs.index') }}" class="al-filter">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search action, module, or description..."
                   class="al-input">

            <select name="module" class="al-select">
                <option value="">All Modules</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                        {{ $module }}
                    </option>
                @endforeach
            </select>

            <input type="date"
                   name="date"
                   value="{{ request('date') }}"
                   class="al-input">

            <div class="al-btn-row">
                <button type="submit" class="al-btn">Apply</button>
                <a href="{{ route('activity-logs.index') }}" class="al-btn-soft">Reset</a>
            </div>

        </form>
    </div>

    <div class="al-card">
        <div class="al-table-wrap">
            <table class="al-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>IP</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="al-row">
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td>{{ $log->action }}</td>
                            <td><span class="al-pill">{{ $log->module }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td class="al-muted">{{ $log->ip_address ?? '-' }}</td>
                            <td class="al-muted">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-box">No activity logs found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection