@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Inventory Logs</h1>
        <p class="text-slate-500">History of stock movements.</p>
    </div>
    <div class="flex justify-end mb-4">
    <a href="{{ route('inventory.logs.export', request()->query()) }}"
       class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">
        Export CSV
    </a>
</div>
    <div class="bg-white rounded-2xl shadow p-6 border mb-6">
    <form method="GET" action="{{ route('inventory.logs') }}" class="grid md:grid-cols-4 gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search product..."
               class="rounded-lg border-slate-300">

        <select name="type" class="rounded-lg border-slate-300">
            <option value="">All Types</option>
            <option value="stock_in" {{ request('type') == 'stock_in' ? 'selected' : '' }}>Stock In</option>
            <option value="stock_out" {{ request('type') == 'stock_out' ? 'selected' : '' }}>Stock Out</option>
        </select>

        <input type="date" name="date" value="{{ request('date') }}"
               class="rounded-lg border-slate-300">

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg">
                Filter
            </button>

            <a href="{{ route('inventory.logs') }}"
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
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Quantity</th>
                        <th class="p-3 text-left">Remarks</th>
                        <th class="p-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="p-3">{{ $log->product->name ?? 'Deleted Product' }}</td>
                            <td class="p-3">
                                @if($log->type === 'stock_in')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Stock In</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Stock Out</span>
                                @endif
                            </td>
                            <td class="p-3">{{ $log->quantity }}</td>
                            <td class="p-3">{{ $log->remarks ?? '-' }}</td>
                            <td class="p-3">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-slate-500">No inventory logs yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection