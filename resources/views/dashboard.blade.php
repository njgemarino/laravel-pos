@extends('layouts.app')

@section('content')
<div class="space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500">Welcome back, {{ auth()->user()->name }}.</p>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-sm text-slate-500">Total Products</p>
            <h2 class="text-4xl font-bold text-blue-600 mt-2">{{ $totalProducts }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-sm text-slate-500">Total Users</p>
            <h2 class="text-4xl font-bold text-rose-600 mt-2">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-sm text-slate-500">Transactions</p>
            <h2 class="text-4xl font-bold text-emerald-600 mt-2">{{ $totalTransactions }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <p class="text-sm text-slate-500">Today's Sales</p>
            <h2 class="text-4xl font-bold text-indigo-600 mt-2">₱{{ number_format($todaySales, 2) }}</h2>
        </div>
    </div>
    <br>
    {{-- CHART + BEST SELLERS --}}
    <div class="grid xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 bg-white rounded-2xl shadow p-6 border">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-xl font-semibold text-slate-800">Monthly Sales Trend</h2>
                    <p class="text-sm text-slate-500">Sales performance for this month</p>
                </div>
            </div>

            <div class="h-96">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Best-Selling Products</h2>

            <div class="space-y-4">
                @forelse($bestSellingProducts as $item)
                    <div class="flex justify-between items-center border rounded-xl px-4 py-3">
                        <div>
                            <p class="font-semibold text-slate-800">{{ $item->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-sm text-slate-500">{{ $item->product->category ?? 'No category' }}</p>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $item->total_sold }} sold
                        </span>
                    </div>
                @empty
                    <div class="text-slate-500 text-sm">No sales data yet.</div>
                @endforelse
            </div>
        </div>
    </div>
<br>
    {{-- LOW STOCK + RECENT TRANSACTIONS --}}
    <div class="grid xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Stock Alerts</h2>

            @forelse($lowStockProducts as $product)
                <div class="flex justify-between items-center border rounded-xl px-4 py-3 mb-3">
                    <div>
                        <p class="font-semibold text-slate-800">{{ $product->name }}</p>
                        <p class="text-sm text-slate-500">{{ $product->category ?? 'No category' }}</p>
                    </div>

                    @if($product->stock <= 0)
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                            Out of Stock
                        </span>
                    @else
                        <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $product->stock }} left
                        </span>
                    @endif
                </div>
            @empty
                <div class="bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-lg p-4">
                    All products currently have healthy stock.
                </div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow p-6 border">
            <h2 class="text-xl font-semibold text-slate-800 mb-4">Recent Transactions</h2>

            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                    <div class="border rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $transaction->receipt_no }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ $transaction->user->name ?? 'N/A' }} • {{ $transaction->created_at->format('M d, Y h:i A') }}
                                </p>
                            </div>
                            <span class="font-bold text-emerald-600">
                                ₱{{ number_format($transaction->total_amount, 2) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-500 text-sm">No transactions yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- CHART.JS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartLabels = @json($monthlySales->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d')));
    const chartData = @json($monthlySales->pluck('total'));

    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Daily Sales',
                data: chartData,
                borderWidth: 3,
                tension: 0.3,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection