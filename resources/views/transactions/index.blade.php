@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Sales History</h1>
        <p class="text-slate-500">All recorded transactions.</p>
    </div>

    <div class="flex justify-end mb-4">
    <a href="{{ route('transactions.export', request()->query()) }}"
       class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">
        Export CSV
    </a>
</div>
    <div class="bg-white rounded-2xl shadow p-6 border mb-6">
    <form method="GET" action="{{ route('transactions.index') }}" class="grid md:grid-cols-3 gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search receipt number..."
               class="rounded-lg border-slate-300">

        <input type="date" name="date" value="{{ request('date') }}"
               class="rounded-lg border-slate-300">

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg">
                Filter
            </button>

            <a href="{{ route('transactions.index') }}"
               class="bg-slate-200 hover:bg-slate-300 text-slate-800 px-4 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>
</div>
    <div class="bg-white rounded-2xl shadow p-6 border">
        <div class="overflow-x-auto">
            <table class="w-full border border-slate-200 rounded-lg overflow-hidden">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="p-3 text-left">Receipt No</th>
                        <th class="p-3 text-left">Cashier</th>
                        <th class="p-3 text-left">Total</th>
                        <th class="p-3 text-left">Payment</th>
                        <th class="p-3 text-left">Change</th>
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-center">Action</th>
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
                            <td class="p-3">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('transactions.show', $transaction) }}"
                                   class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-6 text-center text-slate-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection