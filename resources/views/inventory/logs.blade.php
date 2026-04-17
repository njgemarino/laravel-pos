@extends('layouts.app')

@section('content')

<style>
.logs-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.logs-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.logs-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.logs-toolbar {
    display: flex;
    justify-content: flex-end;
}

.logs-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
}

.logs-filter {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
}

@media (min-width: 768px) {
    .logs-filter {
        grid-template-columns: 1.5fr 1fr 1fr auto;
    }
}

.logs-input,
.logs-select {
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

.logs-input:focus,
.logs-select:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.logs-btn {
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

.logs-btn:hover {
    opacity: .85;
}

.logs-btn-soft {
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

.logs-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}

.logs-btn-row {
    display: flex;
    gap: 6px;
}

.logs-table-wrap {
    overflow-x: auto;
}

.logs-table {
    width: 100%;
    border-collapse: collapse;
}

.logs-table thead {
    background: #faf9f5;
}

.logs-table th {
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #888;
    text-align: left;
    white-space: nowrap;
}

.logs-table td {
    padding: 12px;
    font-size: 12px;
    color: #1a1a1a;
    border-top: 0.5px solid #f0ece0;
    vertical-align: middle;
}

.logs-row:hover {
    background: #fcfbf8;
}

.logs-type {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 10px;
    border: 0.5px solid transparent;
}

.logs-type.in {
    background: #ecfdf5;
    color: #059669;
    border-color: #a7f3d0;
}

.logs-type.out {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

.logs-muted {
    color: #999;
    font-size: 12px;
}

.empty-box {
    text-align: center;
    padding: 24px;
    color: #999;
    font-size: 12px;
}
</style>

<div class="logs-root">

    <div>
        <div class="logs-header-title">Inventory Logs</div>
        <div class="logs-header-sub">History of stock movements</div>
    </div>

    <div class="logs-toolbar">
        <a href="{{ route('inventory.logs.export', request()->query()) }}" class="logs-btn">
            Export CSV
        </a>
    </div>

    <div class="logs-card">
        <form method="GET" action="{{ route('inventory.logs') }}" class="logs-filter">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search product..."
                   class="logs-input">

            <select name="type" class="logs-select">
                <option value="">All Types</option>
                <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
                <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
            </select>

            <input type="date"
                   name="date"
                   value="{{ request('date') }}"
                   class="logs-input">

            <div class="logs-btn-row">
                <button type="submit" class="logs-btn">Apply</button>
                <a href="{{ route('inventory.logs') }}" class="logs-btn-soft">Reset</a>
            </div>

        </form>
    </div>

    <div class="logs-card">
        <div class="logs-table-wrap">
            <table class="logs-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="logs-row">
                            <td>{{ $log->product->name ?? 'Deleted Product' }}</td>
                            <td>
                                @if($log->type === 'stock_in')
                                    <span class="logs-type in">Stock In</span>
                                @else
                                    <span class="logs-type out">Stock Out</span>
                                @endif
                            </td>
                            <td>{{ $log->quantity }}</td>
                            <td>{{ $log->remarks ?? '-' }}</td>
                            <td class="logs-muted">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-box">No inventory logs yet.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection