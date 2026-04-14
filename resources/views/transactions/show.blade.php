@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Transaction Details</h1>
        <p class="text-slate-500">Receipt: {{ $transaction->receipt_no }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border">
        <div class="grid md:grid-cols-2 gap-4 mb-6 text-sm">
            <div>
                <p><span class="font-semibold">Cashier:</span> {{ $transaction->user->name ?? 'N/A' }}</p>
                <p><span class="font-semibold">Date:</span> {{ $transaction->created_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-slate-200 rounded-lg overflow-hidden">
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

        <div class="mt-6 flex justify-end">
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
    </div>
</div>
@endsection