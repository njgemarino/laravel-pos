@extends('layouts.app')

@section('content')

<style>
.products-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

/* HEADER */
.pg-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.pg-title {
    font-size: 18px;
    font-weight: 600;
    color: #0f0f0f;
}
.pg-sub {
    font-size: 12px;
    color: #888;
}

/* BUTTON */
.btn-main {
    background: #0f0f0f;
    color: #fff;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px;
    border: none;
    cursor: pointer;
}
.btn-main:hover { opacity: .85; }

.btn-soft {
    background: #f5f3ec;
    border: 0.5px solid #e5e2d8;
    color: #666;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px;
}

/* FILTER CARD */
.pg-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 14px;
}

.pg-input, .pg-select {
    width: 100%;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 7px 10px;
    font-size: 12px;
    background: #faf9f5;
    outline: none;
}
.pg-input:focus, .pg-select:focus {
    border-color: #2563eb;
}

/* TABLE */
.pg-table {
    width: 100%;
    border-collapse: collapse;
}
.pg-table thead {
    background: #faf9f5;
}
.pg-table th {
    font-size: 11px;
    color: #888;
    text-align: left;
    padding: 10px;
    font-weight: 500;
}
.pg-table td {
    padding: 12px 10px;
    border-top: 0.5px solid #f0ece0;
    font-size: 12px;
    color: #1a1a1a;
}

.pg-row:hover {
    background: #faf9f5;
}

/* STOCK BADGES */
.stock {
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 20px;
}

.stock-ok { background:#ecfdf5; color:#059669; }
.stock-low { background:#fffbeb; color:#d97706; }
.stock-out { background:#fef2f2; color:#dc2626; }

/* ACTIONS */
.actions {
    display: flex;
    gap: 6px;
}
.btn-edit {
    background: #f5f3ec;
    border: 0.5px solid #e5e2d8;
    padding: 6px 10px;
    font-size: 11px;
    border-radius: 6px;
    cursor: pointer;
}
.btn-edit:hover { background: #ece8df; }

.btn-delete {
    background: transparent;
    border: 0.5px solid #fca5a5;
    color: #dc2626;
    padding: 6px 10px;
    font-size: 11px;
    border-radius: 6px;
}
.btn-delete:hover { background: #fef2f2; }

</style>

<div class="products-root">

    {{-- HEADER --}}
    <div class="pg-header">
        <div>
            <div class="pg-title">Products</div>
            <div class="pg-sub">Manage inventory and stock levels</div>
        </div>

        <a href="{{ route('products.create') }}" class="btn-main">
            + Add Product
        </a>
    </div>

    {{-- FILTER --}}
    <div class="pg-card">
        <form method="GET" action="{{ route('products.index') }}"
              style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:8px;">

            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Search product..."
                   class="pg-input">

            <select name="category" class="pg-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <select name="stock_status" class="pg-select">
                <option value="">All Stock</option>
                <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low</option>
                <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out</option>
            </select>

            <div style="display:flex;gap:6px;">
                <button type="submit" class="btn-main">Apply</button>
                <a href="{{ route('products.index') }}" class="btn-soft">Reset</a>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="pg-card">
        <div style="overflow-x:auto;">
            <table class="pg-table">
                <thead>
                    <tr>
                        <th>Barcode</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                        <tr class="pg-row">
                            <td>{{ $product->barcode ?? '-' }}</td>
                            <td style="font-weight:500;">{{ $product->name }}</td>
                            <td>{{ $product->category ?? '-' }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>

                            <td>
                                @if($product->stock <= 0)
                                    <span class="stock stock-out">Out (0)</span>
                                @elseif($product->stock <= 5)
                                    <span class="stock stock-low">{{ $product->stock }} left</span>
                                @else
                                    <span class="stock stock-ok">{{ $product->stock }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions" style="justify-content:center;">
                                    <a href="{{ route('products.edit', $product) }}" class="btn-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                          onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;padding:20px;color:#999;">
                                No products found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection