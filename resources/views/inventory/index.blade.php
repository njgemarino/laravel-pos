@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Stock Update</h1>
        <p class="text-slate-500">Add or deduct stock for products.</p>
    </div>

    <div class="grid gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-2xl shadow p-6 border">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-800">{{ $product->name }}</h2>
                        <p class="text-slate-500 text-sm">Category: {{ $product->category ?? 'N/A' }}</p>
                        <p class="text-slate-600 mt-1">
                            Current Stock:
                            <span class="font-bold">{{ $product->stock }}</span>
                        </p>
                    </div>

                    <form action="{{ route('inventory.updateStock', $product) }}" method="POST" class="grid md:grid-cols-4 gap-3 w-full md:w-auto">
                        @csrf

                        <select name="type" class="rounded-lg border-slate-300" required>
                            <option value="stock_in">Stock In</option>
                            <option value="stock_out">Stock Out</option>
                        </select>

                        <input type="number" name="quantity" min="1" placeholder="Quantity"
                               class="rounded-lg border-slate-300" required>

                        <input type="text" name="remarks" placeholder="Remarks"
                               class="rounded-lg border-slate-300">

                        <button type="submit"
                                class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow p-6 border text-slate-500 text-center">
                No products found.
            </div>
        @endforelse
    </div>
</div>
@endsection