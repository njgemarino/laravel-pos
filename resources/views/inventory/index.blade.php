@extends('layouts.app')

@section('content')

<style>
.inv-root {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.inv-header-title {
    font-size: 18px;
    font-weight: 500;
    color: #0f0f0f;
}

.inv-header-sub {
    font-size: 12px;
    color: #999;
    margin-top: 2px;
}

.inv-card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 16px;
}

.inv-item {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

@media (min-width: 768px) {
    .inv-item {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.inv-name {
    font-size: 15px;
    font-weight: 500;
    color: #0f0f0f;
    margin-bottom: 2px;
}

.inv-meta {
    font-size: 11px;
    color: #999;
}

.stock-line {
    margin-top: 6px;
    font-size: 12px;
    color: #666;
}

.stock-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    padding: 3px 8px;
    border-radius: 999px;
    margin-left: 8px;
    border: 0.5px solid transparent;
}

.stock-pill.ok {
    background: #ecfdf5;
    color: #059669;
    border-color: #a7f3d0;
}

.stock-pill.low {
    background: #fffbeb;
    color: #d97706;
    border-color: #fcd34d;
}

.stock-pill.out {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fca5a5;
}

.inv-form {
    display: grid;
    grid-template-columns: 1fr;
    gap: 8px;
    width: 100%;
}

@media (min-width: 768px) {
    .inv-form {
        grid-template-columns: 120px 100px 1fr 110px;
        width: auto;
        min-width: 560px;
    }
}

.inv-select,
.inv-input {
    width: 100%;
    background: #faf9f5;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 10px;
    font-size: 12px;
    color: #0f0f0f;
    outline: none;
    font-family: inherit;
    transition: border-color .15s, background .15s;
}

.inv-select:focus,
.inv-input:focus {
    border-color: #0f0f0f;
    background: #fff;
}

.inv-btn {
    background: #0f0f0f;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity .15s;
    font-family: inherit;
}

.inv-btn:hover {
    opacity: .85;
}

.empty-box {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    font-size: 12px;
    color: #999;
}
</style>

<div class="inv-root">

    <div>
        <div class="inv-header-title">Stock Update</div>
        <div class="inv-header-sub">Add or deduct stock for products</div>
    </div>

    <div class="space-y-4">
        @forelse($products as $product)
            <div class="inv-card">
                <div class="inv-item">

                    <div>
                        <div class="inv-name">{{ $product->name }}</div>
                        <div class="inv-meta">
                            {{ $product->category ?? 'No category' }}
                        </div>

                        <div class="stock-line">
                            Current Stock: <strong>{{ $product->stock }}</strong>

                            @if($product->stock <= 0)
                                <span class="stock-pill out">Out of stock</span>
                            @elseif($product->stock <= 5)
                                <span class="stock-pill low">Low stock</span>
                            @else
                                <span class="stock-pill ok">Healthy stock</span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('inventory.updateStock', $product) }}" method="POST" class="inv-form">
                        @csrf

                        <select name="type" class="inv-select" required>
                            <option value="stock_in">Stock In</option>
                            <option value="stock_out">Stock Out</option>
                        </select>

                        <input type="number"
                               name="quantity"
                               min="1"
                               placeholder="Quantity"
                               class="inv-input"
                               required>

                        <input type="text"
                               name="remarks"
                               placeholder="Remarks"
                               class="inv-input">

                        <button type="submit" class="inv-btn">
                            Update
                        </button>
                    </form>

                </div>
            </div>
        @empty
            <div class="empty-box">
                No products found.
            </div>
        @endforelse
    </div>

</div>

@endsection