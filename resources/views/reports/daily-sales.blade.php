@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div class="flex gap-3">
            <a href="{{ route('reports.daily-sales.export', ['date' => $date]) }}"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">
                Export CSV
            </a>

            <button onclick="window.print()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Print Report
            </button>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Daily Sales Report</h1>
            <p class="text-slate-500">Sales summary for selected date.</p>
        </div>

        <form method="GET" action="{{ route('reports.daily-sales') }}" class="flex gap-3">
            <input type="date" name="date" value="{{ $date }}"
                   class="rounded-lg border-slate-300">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg">
                Filter
            </button>
        </form>
    </div>

   <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-sm text-slate-500 mb-2">Total Sales</h2>
            <p class="text-3xl font-bold text-emerald-600">₱{{ number_format($totalSales, 2) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-sm text-slate-500 mb-2">Transactions</h2>
            <p class="text-3xl font-bold text-slate-800">{{ $totalTransactions }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-sm text-slate-500 mb-2">Items Sold</h2>
            <p class="text-3xl font-bold text-cyan-600">{{ $itemsSold }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-sm text-slate-500 mb-2">Average Sale</h2>
            <p class="text-3xl font-bold text-indigo-600">₱{{ number_format($averageSale, 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 border">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-800">Transactions</h2>
                <p class="text-sm text-slate-500">Detailed list of sales for {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p>
            </div>

            <button onclick="window.print()"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                Print Report
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-3 text-left">Receipt No</th>
                        <th class="p-3 text-left">Cashier</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Payment</th>
                        <th class="p-3 text-left">Change</th>
                        <th class="p-3 text-left">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">{{ $transaction->receipt_no }}</td>
                            <td class="p-3">{{ $transaction->user->name ?? 'N/A' }}</td>
                            <td class="p-3">₱{{ number_format($transaction->total_amount, 2) }}</td>
                            <td class="p-3">₱{{ number_format($transaction->payment, 2) }}</td>
                            <td class="p-3">₱{{ number_format($transaction->change_amount, 2) }}</td>
                            <td class="p-3">{{ $transaction->created_at->format('h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500">No sales recorded for this date.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection