@extends('layouts.app')

@section('content')

<style>
.tx-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.tx-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.tx-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.tx-toolbar {
    display: flex;
    justify-content: flex-end;
}

.tx-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
}

.tx-filter {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
}

@media (min-width: 768px) {
    .tx-filter {
        grid-template-columns: 1.5fr 1fr auto;
    }
}

.tx-input {
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

.tx-input:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.tx-btn {
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

.tx-btn:hover {
    opacity: .85;
}

.tx-btn-soft {
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

.tx-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}

.tx-btn-row {
    display: flex;
    gap: 6px;
}

.tx-table-wrap {
    overflow-x: auto;
}

.tx-table {
    width: 100%;
    border-collapse: collapse;
}

.tx-table thead {
    background: #faf9f5;
}

.tx-table th {
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #888;
    text-align: left;
    white-space: nowrap;
}

.tx-table td {
    padding: 12px;
    font-size: 12px;
    color: #1a1a1a;
    border-top: 0.5px solid #f0ece0;
    vertical-align: middle;
}

.tx-row:hover {
    background: #fcfbf8;
}

.tx-muted {
    color: #999;
    font-size: 12px;
}

.tx-amount {
    font-weight: 500;
    color: #0f0f0f;
    white-space: nowrap;
}

.tx-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #0f0f0f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 7px 12px;
    font-size: 11px;
    font-weight: 500;
    text-decoration: none;
    transition: opacity .15s;
}

.tx-action:hover {
    opacity: .85;
}

.empty-box {
    text-align: center;
    padding: 24px;
    color: #999;
    font-size: 12px;
}
</style>

<div class="tx-root">

    <div>
        <div class="tx-header-title">Sales History</div>
        <div class="tx-header-sub">All recorded transactions</div>
    </div>

    <div class="tx-toolbar">
        <a href="{{ route('transactions.export', request()->query()) }}" class="tx-btn">
            Export CSV
        </a>
    </div>

    <div class="tx-card">
        <form method="GET" action="{{ route('transactions.index') }}" class="tx-filter">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search receipt number..."
                   class="tx-input">

            <input type="date"
                   name="date"
                   value="{{ request('date') }}"
                   class="tx-input">

            <div class="tx-btn-row">
                <button type="submit" class="tx-btn">Apply</button>
                <a href="{{ route('transactions.index') }}" class="tx-btn-soft">Reset</a>
            </div>

        </form>
    </div>

    <div class="tx-card">
        <div class="tx-table-wrap">
            <table class="tx-table">
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Change</th>
                        <th>Date</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="tx-row">
                            <td>{{ $transaction->receipt_no }}</td>
                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td class="tx-amount">₱{{ number_format($transaction->total_amount, 2) }}</td>
                            <td class="tx-amount">₱{{ number_format($transaction->payment, 2) }}</td>
                            <td class="tx-amount">₱{{ number_format($transaction->change_amount, 2) }}</td>
                            <td class="tx-muted">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('transactions.show', $transaction) }}" class="tx-action">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-box">No transactions found.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection