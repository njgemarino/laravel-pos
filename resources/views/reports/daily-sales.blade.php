@extends('layouts.app')

@section('content')

<style>
.report-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.report-top {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

@media (min-width: 768px) {
    .report-top {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.report-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.report-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.report-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.report-btn {
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

.report-btn:hover {
    opacity: .85;
}

.report-btn-soft {
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

.report-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}

.report-filter {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.report-input {
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

.report-input:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.report-stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
}

@media (min-width: 768px) {
    .report-stats {
        grid-template-columns: repeat(4, 1fr);
    }
}

.report-stat {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 16px;
}

.report-stat-label {
    font-size: 11px;
    color: #999;
    margin-bottom: 6px;
}

.report-stat-value {
    font-size: 24px;
    font-weight: 600;
    color: #0f0f0f;
}

.report-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
}

.report-card-head {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 14px;
}

@media (min-width: 768px) {
    .report-card-head {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.report-card-title {
    font-size: 15px;
    font-weight: 500;
    color: #0f0f0f;
}

.report-card-sub {
    font-size: 11px;
    color: #999;
    margin-top: 2px;
}

.report-table-wrap {
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table thead {
    background: #faf9f5;
}

.report-table th {
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #888;
    text-align: left;
    white-space: nowrap;
}

.report-table td {
    padding: 12px;
    font-size: 12px;
    color: #1a1a1a;
    border-top: 0.5px solid #f0ece0;
    vertical-align: middle;
}

.report-row:hover {
    background: #fcfbf8;
}

.report-money {
    font-weight: 500;
    color: #0f0f0f;
    white-space: nowrap;
}

.report-muted {
    color: #999;
    font-size: 12px;
}

.empty-box {
    text-align: center;
    padding: 24px;
    color: #999;
    font-size: 12px;
}

@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: #fff !important;
    }

    .report-card,
    .report-stat {
        border: 0.5px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>

<div class="report-root">

    <div class="report-top">

        <div>
            <div class="report-title">Daily Sales Report</div>
            <div class="report-sub">Sales summary for selected date</div>
        </div>

        <div class="report-actions no-print">
            <a href="{{ route('reports.daily-sales.export', ['date' => $date]) }}" class="report-btn">
                Export CSV
            </a>

            <button onclick="window.print()" class="report-btn-soft">
                Print Report
            </button>
        </div>

    </div>

    <div class="report-card no-print">
        <form method="GET" action="{{ route('reports.daily-sales') }}" class="report-filter">
            <input type="date" name="date" value="{{ $date }}" class="report-input">

            <button type="submit" class="report-btn">
                Apply
            </button>
        </form>
    </div>

    <div class="report-stats">
        <div class="report-stat">
            <div class="report-stat-label">Total Sales</div>
            <div class="report-stat-value">₱{{ number_format($totalSales, 2) }}</div>
        </div>

        <div class="report-stat">
            <div class="report-stat-label">Transactions</div>
            <div class="report-stat-value">{{ $totalTransactions }}</div>
        </div>

        <div class="report-stat">
            <div class="report-stat-label">Items Sold</div>
            <div class="report-stat-value">{{ $itemsSold }}</div>
        </div>

        <div class="report-stat">
            <div class="report-stat-label">Average Sale</div>
            <div class="report-stat-value">₱{{ number_format($averageSale, 2) }}</div>
        </div>
    </div>

    <div class="report-card">
        <div class="report-card-head">
            <div>
                <div class="report-card-title">Transactions</div>
                <div class="report-card-sub">
                    Detailed sales for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}
                </div>
            </div>

            <button onclick="window.print()" class="report-btn-soft no-print">
                Print
            </button>
        </div>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Change</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="report-row">
                            <td>{{ $transaction->receipt_no }}</td>
                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td class="report-money">₱{{ number_format($transaction->total_amount, 2) }}</td>
                            <td class="report-money">₱{{ number_format($transaction->payment, 2) }}</td>
                            <td class="report-money">₱{{ number_format($transaction->change_amount, 2) }}</td>
                            <td class="report-muted">{{ $transaction->created_at->format('h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-box">No sales recorded for this date.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection