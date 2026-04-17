<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use LogsActivity;
    
    public function index(Request $request)
{
    $query = Product::query();

    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->category) {
        $query->where('category', $request->category);
    }

    if ($request->stock_status) {
        if ($request->stock_status === 'in_stock') {
            $query->where('stock', '>', 5);
        } elseif ($request->stock_status === 'low_stock') {
            $query->whereBetween('stock', [1, 5]);
        } elseif ($request->stock_status === 'out_of_stock') {
            $query->where('stock', '<=', 0);
        }
    }

    $products = $query->latest()->get();

    $categories = Product::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category');

    return view('products.index', compact('products', 'categories'));
}

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'barcode' => 'nullable|unique:products,barcode',
            'name' => 'required',
            'category' => 'nullable',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0'
        ]);

        Product::create($request->only([
            'barcode',
            'name',
            'category',
            'price',
            'stock'
        ]));
        

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
        $this->logActivity('Create', 'Products', 'Created product: ' . $product->name);
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'barcode' => 'nullable|unique:products,barcode,' . $product->id,
            'name' => 'required',
            'category' => 'nullable',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0'
        ]);

        $product->update($request->only([
            'barcode',
            'name',
            'category',
            'price',
            'stock'
        ]));

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        $this->logActivity('Update', 'Products', 'Updated product: ' . $product->name);
    }

    public function destroy(Product $product)
    {
        if ($product->saleItems()->count() > 0) {
            return redirect()->route('products.index')->with('error', 'Cannot delete product already used in sales.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        $this->logActivity('Delete', 'Products', 'Deleted product: ' . $product->name);
    }
}