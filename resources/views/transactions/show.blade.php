@extends('layouts.app')

@section('content')

<style>
.txshow-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.txshow-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.txshow-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.txshow-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 16px;
}

.txshow-meta {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
    margin-bottom: 16px;
}

@media (min-width: 768px) {
    .txshow-meta {
        grid-template-columns: 1fr 1fr;
    }
}

.txshow-meta-box {
    background: #faf9f5;
    border: 0.5px solid #eee7da;
    border-radius: 10px;
    padding: 12px;
}

.txshow-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #bbb;
    margin-bottom: 4px;
}

.txshow-value {
    font-size: 13px;
    color: #1a1a1a;
    font-weight: 500;
}

.txshow-table-wrap {
    overflow-x: auto;
}

.txshow-table {
    width: 100%;
    border-collapse: collapse;
}

.txshow-table thead {
    background: #faf9f5;
}

.txshow-table th {
    padding: 10px 12px;
    font-size: 11px;
    font-weight: 500;
    color: #888;
    text-align: left;
    white-space: nowrap;
}

.txshow-table td {
    padding: 12px;
    font-size: 12px;
    color: #1a1a1a;
    border-top: 0.5px solid #f0ece0;
    vertical-align: middle;
}

.txshow-row:hover {
    background: #fcfbf8;
}

.txshow-money {
    font-weight: 500;
    color: #0f0f0f;
    white-space: nowrap;
}

.txshow-summary-wrap {
    margin-top: 16px;
    display: flex;
    justify-content: flex-end;
}

.txshow-summary {
    width: 100%;
    max-width: 320px;
    background: #faf9f5;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.txshow-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 12px;
    color: #666;
}

.txshow-summary-row strong {
    color: #0f0f0f;
    font-weight: 500;
}

.txshow-summary-total {
    border-top: 0.5px solid #e5e2d8;
    margin-top: 6px;
    padding-top: 10px;
    font-size: 14px;
    font-weight: 600;
    color: #0f0f0f;
}

.txshow-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 4px;
}

.txshow-btn {
    background: #0f0f0f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity .15s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.txshow-btn:hover {
    opacity: .85;
}

.txshow-btn-soft {
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

.txshow-btn-soft:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}
</style>

<div class="txshow-root">

    <div>
        <div class="txshow-header-title">Transaction Details</div>
        <div class="txshow-header-sub">Receipt: {{ $transaction->receipt_no }}</div>
    </div>

    <div class="txshow-card">

        <div class="txshow-meta">
            <div class="txshow-meta-box">
                <div class="txshow-label">Cashier</div>
                <div class="txshow-value">{{ $transaction->user->name ?? 'N/A' }}</div>
            </div>

            <div class="txshow-meta-box">
                <div class="txshow-label">Transaction Date</div>
                <div class="txshow-value">{{ $transaction->created_at->format('F d, Y h:i A') }}</div>
            </div>
        </div>

        <div class="txshow-table-wrap">
            <table class="txshow-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaction->items as $item)
                        <tr class="txshow-row">
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="txshow-money">₱{{ number_format($item->price, 2) }}</td>
                            <td class="txshow-money">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding:20px;text-align:center;color:#999;">
                                No items found for this transaction.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="txshow-summary-wrap">
            <div class="txshow-summary">
                <div class="txshow-summary-row">
                    <span>Total</span>
                    <strong>₱{{ number_format($transaction->total_amount, 2) }}</strong>
                </div>
                <div class="txshow-summary-row">
                    <span>Payment</span>
                    <strong>₱{{ number_format($transaction->payment, 2) }}</strong>
                </div>
                <div class="txshow-summary-row txshow-summary-total">
                    <span>Change</span>
                    <strong>₱{{ number_format($transaction->change_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="txshow-actions">
            <a href="{{ route('transactions.index') }}" class="txshow-btn-soft">
                Back
            </a>
            <a href="{{ route('sales.receipt', $transaction->id) }}" class="txshow-btn">
                View Receipt
            </a>
        </div>

    </div>

</div>

@endsection