@extends('layouts.app')

@section('content')

<style>
/* reuse same styles */
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
            <div class="card-title">Edit Product</div>
            <div class="card-sub">Update product details</div>
        </div>

        @if ($errors->any())
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:10px;border-radius:8px;font-size:12px;">
                <ul style="margin:0;padding-left:16px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="input-group">
                <label class="label">Barcode</label>
                <input name="barcode" value="{{ old('barcode', $product->barcode) }}" class="input">
            </div>

            <div class="input-group">
                <label class="label">Product Name</label>
                <input name="name" value="{{ old('name', $product->name) }}" class="input" required>
            </div>

            <div class="input-group">
                <label class="label">Category</label>
                <input name="category" value="{{ old('category', $product->category) }}" class="input">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                <div class="input-group">
                    <label class="label">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="input" required>
                </div>

                <div class="input-group">
                    <label class="label">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="input" required>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:10px;">
                <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">Update Product</button>
            </div>

        </form>

    </div>

</div>

@endsection