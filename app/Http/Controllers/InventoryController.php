<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory.index', compact('products'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        if ($request->type === 'stock_out' && $product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock to deduct.');
        }

        if ($request->type === 'stock_in') {
            $product->increment('stock', $request->quantity);
        } else {
            $product->decrement('stock', $request->quantity);
        }

        InventoryTransaction::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Stock updated successfully.');
    }

    public function logs(Request $request)
{
    $query = InventoryTransaction::with('product');

    if ($request->search) {
        $query->whereHas('product', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->type) {
        $query->where('type', $request->type);
    }

    if ($request->date) {
        $query->whereDate('created_at', $request->date);
    }

    $logs = $query->latest()->get();

    return view('inventory.logs', compact('logs'));
}

public function export(Request $request)
{
    $query = InventoryTransaction::with('product');

    if ($request->search) {
        $query->whereHas('product', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->type) {
        $query->where('type', $request->type);
    }

    if ($request->date) {
        $query->whereDate('created_at', $request->date);
    }

    $logs = $query->latest()->get();

    $filename = 'inventory_logs_' . now()->format('Ymd_His') . '.csv';

    $response = new StreamedResponse(function () use ($logs) {
        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'Product',
            'Type',
            'Quantity',
            'Remarks',
            'Date'
        ]);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->product->name ?? 'N/A',
                $log->type,
                $log->quantity,
                $log->remarks,
                $log->created_at->format('Y-m-d h:i A'),
            ]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    return $response;
}

}