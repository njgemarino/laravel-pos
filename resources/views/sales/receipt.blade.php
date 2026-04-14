<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->receipt_no }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .receipt-card {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10">

    <div class="max-w-2xl mx-auto px-4">
        <div class="no-print mb-6 flex justify-between items-center">
            <a href="{{ route('sales.index') }}"
               class="bg-slate-700 hover:bg-slate-800 text-white px-4 py-2 rounded-lg">
                ← Back to POS
            </a>

            <button onclick="window.print()"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">
                Print Receipt
            </button>
        </div>

        <div class="receipt-card bg-white rounded-2xl shadow-lg border p-8">
            <div class="text-center border-b pb-6 mb-6">
                <h1 class="text-3xl font-bold text-slate-800">POS System</h1>
                <p class="text-slate-500">Official Sales Receipt</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4 text-sm mb-6">
                <div>
                    <p><span class="font-semibold">Receipt No:</span> {{ $transaction->receipt_no }}</p>
                    <p><span class="font-semibold">Date:</span> {{ $transaction->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div class="md:text-right">
                    <p><span class="font-semibold">Cashier:</span> {{ $transaction->user->name ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border border-slate-200 rounded-lg overflow-hidden text-sm">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="p-3 text-left">Product</th>
                            <th class="p-3 text-left">Qty</th>
                            <th class="p-3 text-left">Price</th>
                            <th class="p-3 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($transaction->items as $item)
                            <tr>
                                <td class="p-3">{{ $item->product->name }}</td>
                                <td class="p-3">{{ $item->quantity }}</td>
                                <td class="p-3">₱{{ number_format($item->price, 2) }}</td>
                                <td class="p-3">₱{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-end">
                <div class="w-full md:w-80 bg-slate-50 border rounded-xl p-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Total:</span>
                        <span class="font-semibold">₱{{ number_format($transaction->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment:</span>
                        <span class="font-semibold">₱{{ number_format($transaction->payment, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-3">
                        <span>Change:</span>
                        <span>₱{{ number_format($transaction->change_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-10 text-sm text-slate-500 border-t pt-6">
                <p>Thank you for your purchase!</p>
                <p>Please keep this receipt for your reference.</p>
            </div>
        </div>
    </div>

</body>
</html>