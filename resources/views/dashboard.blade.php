@extends('layouts.app')

@section('content')

<style>
.dashboard-root {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* HEADER */
.db-header h1 {
    font-size: 22px;
    font-weight: 600;
    color: #0f0f0f;
}
.db-header p {
    font-size: 13px;
    color: #888;
    margin-top: 4px;
}

/* CARDS */
.db-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.db-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 16px;
}

.db-label {
    font-size: 11px;
    color: #999;
    margin-bottom: 6px;
}

.db-value {
    font-size: 24px;
    font-weight: 600;
    color: #0f0f0f;
}

/* PANELS */
.db-panel {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 16px;
}

.db-panel-title {
    font-size: 14px;
    font-weight: 500;
    color: #0f0f0f;
}

.db-panel-sub {
    font-size: 11px;
    color: #999;
}

/* LIST ITEMS */
.db-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 0.5px solid #f0ece0;
    border-radius: 10px;
    padding: 10px 12px;
}

.db-item-title {
    font-size: 12px;
    font-weight: 500;
    color: #1a1a1a;
}

.db-item-sub {
    font-size: 10px;
    color: #aaa;
}

/* BADGES */
.badge {
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 20px;
}

.badge-green {
    background: #ecfdf5;
    color: #059669;
}

.badge-yellow {
    background: #fffbeb;
    color: #d97706;
}

.badge-red {
    background: #fef2f2;
    color: #dc2626;
}
</style>

<div class="dashboard-root">

    {{-- HEADER --}}
    <div class="db-header">
        <h1>Dashboard</h1>
        <p>Overview of your POS activity</p>
    </div>

    {{-- SUMMARY --}}
    <div class="db-grid">
        <div class="db-card">
            <div class="db-label">Total Products</div>
            <div class="db-value">{{ $totalProducts }}</div>
        </div>

        <div class="db-card">
            <div class="db-label">Total Users</div>
            <div class="db-value">{{ $totalUsers }}</div>
        </div>

        <div class="db-card">
            <div class="db-label">Transactions</div>
            <div class="db-value">{{ $totalTransactions }}</div>
        </div>

        <div class="db-card">
            <div class="db-label">Today's Sales</div>
            <div class="db-value">₱{{ number_format($todaySales, 2) }}</div>
        </div>
    </div>

    {{-- CHART + BEST SELLERS --}}
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;">
        
        <div class="db-panel">
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                <div>
                    <div class="db-panel-title">Sales Trend</div>
                    <div class="db-panel-sub">This month</div>
                </div>
            </div>

            <div style="height:260px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="db-panel">
            <div class="db-panel-title" style="margin-bottom:10px;">
                Best Sellers
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($bestSellingProducts as $item)
                    <div class="db-item">
                        <div>
                            <div class="db-item-title">
                                {{ $item->product->name ?? 'Unknown' }}
                            </div>
                            <div class="db-item-sub">
                                {{ $item->product->category ?? '—' }}
                            </div>
                        </div>

                        <span class="badge badge-green">
                            {{ $item->total_sold }}
                        </span>
                    </div>
                @empty
                    <div class="db-item-sub">No data</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- STOCK + RECENT --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">

        <div class="db-panel">
            <div class="db-panel-title" style="margin-bottom:10px;">
                Stock Alerts
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($lowStockProducts as $product)
                    <div class="db-item">
                        <div>
                            <div class="db-item-title">{{ $product->name }}</div>
                            <div class="db-item-sub">{{ $product->category ?? '—' }}</div>
                        </div>

                        @if($product->stock <= 0)
                            <span class="badge badge-red">Out</span>
                        @else
                            <span class="badge badge-yellow">{{ $product->stock }}</span>
                        @endif
                    </div>
                @empty
                    <div class="db-item-sub">All stocks are good</div>
                @endforelse
            </div>
        </div>

        <div class="db-panel">
            <div class="db-panel-title" style="margin-bottom:10px;">
                Recent Transactions
            </div>

            <div style="display:flex;flex-direction:column;gap:8px;">
                @forelse($recentTransactions as $transaction)
                    <div class="db-item">
                        <div>
                            <div class="db-item-title">
                                {{ $transaction->receipt_no }}
                            </div>
                            <div class="db-item-sub">
                                {{ $transaction->created_at->format('M d, h:i A') }}
                            </div>
                        </div>

                        <div style="font-size:13px;font-weight:500;">
                            ₱{{ number_format($transaction->total_amount, 2) }}
                        </div>
                    </div>
                @empty
                    <div class="db-item-sub">No transactions</div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const labels = @json($monthlySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d')));
const data = @json($monthlySales->pluck('total'));

new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            borderWidth: 2,
            tension: 0.3
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#f0ece0' } }
        }
    }
});
</script>

@endsection