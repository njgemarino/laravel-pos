<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->receipt_no }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #f5f4f0;
            font-family: 'DM Sans', sans-serif;
        }

        .receipt-shell {
            max-width: 760px;
            margin: 40px auto;
            padding: 0 16px;
        }

        .receipt-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .receipt-btn {
            background: #0f0f0f;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: opacity .15s;
            font-family: inherit;
        }

        .receipt-btn:hover {
            opacity: .85;
        }

        .receipt-btn-soft {
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
            font-family: inherit;
        }

        .receipt-btn-soft:hover {
            background: #f5f3ec;
            color: #0f0f0f;
        }

        .receipt-card {
            background: #fff;
            border: 0.5px solid #e5e2d8;
            border-radius: 12px;
            padding: 24px;
        }

        .receipt-head {
            text-align: center;
            border-bottom: 0.5px solid #e5e2d8;
            padding-bottom: 18px;
            margin-bottom: 18px;
        }

        .receipt-title {
            font-size: 22px;
            font-weight: 600;
            color: #0f0f0f;
            margin-bottom: 4px;
        }

        .receipt-sub {
            font-size: 12px;
            color: #999;
        }

        .receipt-meta {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }

        @media (min-width: 768px) {
            .receipt-meta {
                grid-template-columns: 1fr 1fr;
            }
        }

        .receipt-meta-box {
            background: #faf9f5;
            border: 0.5px solid #eee7da;
            border-radius: 10px;
            padding: 12px;
        }

        .receipt-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #bbb;
            margin-bottom: 4px;
        }

        .receipt-value {
            font-size: 13px;
            color: #1a1a1a;
            font-weight: 500;
        }

        .receipt-table-wrap {
            overflow-x: auto;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
        }

        .receipt-table thead {
            background: #faf9f5;
        }

        .receipt-table th {
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 500;
            color: #888;
            text-align: left;
            white-space: nowrap;
        }

        .receipt-table td {
            padding: 12px;
            font-size: 12px;
            color: #1a1a1a;
            border-top: 0.5px solid #f0ece0;
            vertical-align: middle;
        }

        .receipt-row:hover {
            background: #fcfbf8;
        }

        .receipt-money {
            font-weight: 500;
            color: #0f0f0f;
            white-space: nowrap;
        }

        .receipt-summary-wrap {
            margin-top: 18px;
            display: flex;
            justify-content: flex-end;
        }

        .receipt-summary {
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

        .receipt-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: #666;
        }

        .receipt-summary-row strong {
            color: #0f0f0f;
            font-weight: 500;
        }

        .receipt-summary-total {
            border-top: 0.5px solid #e5e2d8;
            margin-top: 6px;
            padding-top: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #0f0f0f;
        }

        .receipt-foot {
            text-align: center;
            margin-top: 22px;
            padding-top: 16px;
            border-top: 0.5px solid #e5e2d8;
            font-size: 12px;
            color: #999;
            line-height: 1.7;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .receipt-shell {
                margin: 0;
                max-width: 100%;
                padding: 0;
            }

            .receipt-card {
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="receipt-shell">

        <div class="receipt-toolbar no-print">
            <a href="{{ route('sales.index') }}" class="receipt-btn-soft">
                Back to POS
            </a>

            <button onclick="window.print()" class="receipt-btn">
                Print Receipt
            </button>
        </div>

        <div class="receipt-card">

            <div class="receipt-head">
                <div class="receipt-title">POS System</div>
                <div class="receipt-sub">Official Sales Receipt</div>
            </div>

            <div class="receipt-meta">
                <div class="receipt-meta-box">
                    <div class="receipt-label">Receipt No</div>
                    <div class="receipt-value">{{ $transaction->receipt_no }}</div>
                </div>

                <div class="receipt-meta-box">
                    <div class="receipt-label">Cashier</div>
                    <div class="receipt-value">{{ $transaction->user->name ?? 'N/A' }}</div>
                </div>

                <div class="receipt-meta-box" style="grid-column: 1 / -1;">
                    <div class="receipt-label">Transaction Date</div>
                    <div class="receipt-value">{{ $transaction->created_at->format('F d, Y h:i A') }}</div>
                </div>
            </div>

            <div class="receipt-table-wrap">
                <table class="receipt-table">
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
                            <tr class="receipt-row">
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="receipt-money">₱{{ number_format($item->price, 2) }}</td>
                                <td class="receipt-money">₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:20px;text-align:center;color:#999;">
                                    No items found for this receipt.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="receipt-summary-wrap">
                <div class="receipt-summary">
                    <div class="receipt-summary-row">
                        <span>Total</span>
                        <strong>₱{{ number_format($transaction->total_amount, 2) }}</strong>
                    </div>
                    <div class="receipt-summary-row">
                        <span>Payment</span>
                        <strong>₱{{ number_format($transaction->payment, 2) }}</strong>
                    </div>
                    <div class="receipt-summary-row receipt-summary-total">
                        <span>Change</span>
                        <strong>₱{{ number_format($transaction->change_amount, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="receipt-foot">
                <div>Thank you for your purchase.</div>
                <div>Please keep this receipt for your reference.</div>
            </div>

        </div>
    </div>

</body>
</html>