@extends('layouts.app')

@section('content')

<style>
.card {
    background: #fff;
    border: 0.5px solid #e5e2d8;
    border-radius: 12px;
    padding: 20px;
}

.card-title {
    font-size: 16px;
    font-weight: 500;
    color: #0f0f0f;
}

.card-sub {
    font-size: 12px;
    color: #999;
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.label {
    font-size: 10px;
    text-transform: uppercase;
    color: #bbb;
    letter-spacing: .08em;
}

.input {
    background: #faf9f5;
    border: 0.5px solid #e5e2d8;
    border-radius: 8px;
    padding: 9px 10px;
    font-size: 13px;
    outline: none;
    transition: border-color .15s;
}

.input:focus {
    border-color: #0f0f0f;
}

.btn-primary {
    background: #0f0f0f;
    color: #fff;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: opacity .15s;
}

.btn-primary:hover {
    opacity: .85;
}

.btn-secondary {
    background: transparent;
    border: 0.5px solid #e5e2d8;
    color: #888;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    text-decoration: none;
}

.btn-secondary:hover {
    background: #f5f3ec;
    color: #0f0f0f;
}
</style>

<div class="max-w-xl mx-auto">

    <div class="card space-y-5">

        <div>
            <div class="card-title">Add Product</div>
            <div class="card-sub">Create a new item in your inventory</div>
        </div>

        <form method="POST" action="{{ route('products.store') }}" class="space-y-4">
            @csrf

            <div class="input-group">
                <label class="label">Barcode</label>
                <input name="barcode" class="input" placeholder="Optional barcode">
            </div>

            <div class="input-group">
                <label class="label">Product Name</label>
                <input name="name" class="input" required placeholder="Enter product name">
            </div>

            <div class="input-group">
                <label class="label">Category</label>
                <input name="category" class="input" placeholder="e.g. Beverages">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="input-group">
                    <label class="label">Price</label>
                    <input name="price" type="number" step="0.01" class="input" required placeholder="₱0.00">
                </div>

                <div class="input-group">
                    <label class="label">Stock</label>
                    <input name="stock" type="number" class="input" required placeholder="0">
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:10px;">
                <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
                <button class="btn-primary">Save Product</button>
            </div>

        </form>

    </div>

</div>

@endsection