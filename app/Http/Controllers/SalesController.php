<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\SaleItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required',
            'payment' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $cart = json_decode($request->cart, true);

            if (!$cart || count($cart) === 0) {
                return back()->with('error', 'Cart is empty.');
            }

            /* =========================
               PAYMENT VALIDATION
            ========================= */
            if ($request->payment_method === 'gcash') {
                if (!$request->reference || !ctype_digit($request->reference) || strlen($request->reference) !== 4) {
                    return back()->with('error', 'GCash reference must be exactly 4 digits.');
                }
            }

            if ($request->payment_method === 'card') {
                if (!$request->reference || !ctype_digit($request->reference) || strlen($request->reference) !== 4) {
                    return back()->with('error', 'Card last 4 digits must be valid.');
                }

                if (!$request->card_type) {
                    return back()->with('error', 'Card type is required.');
                }
            }

            if ($request->payment_method === 'cash') {
                if ($request->payment < $request->total) {
                    return back()->with('error', 'Insufficient payment.');
                }
            }

            /* =========================
               BUILD ITEMS FROM CART
            ========================= */
            $items = [];

            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);
                $qty = (int) $item['qty'];

                if ($product->stock < $qty) {
                    return back()->with('error', 'Not enough stock for ' . $product->name);
                }

                $items[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $qty,
                ];
            }

            /* =========================
               CREATE TRANSACTION
            ========================= */
            $payment = $request->payment ?? $request->total;

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'receipt_no' => 'RCPT-' . now()->format('YmdHis'),

                'total_amount' => $request->total,
                'tax' => $request->tax,

                'payment_method' => $request->payment_method,
                'payment' => $payment,
                'change_amount' => $payment - $request->total,

                'reference' => $request->reference,
                'card_type' => $request->card_type,
            ]);

            /* =========================
               SAVE ITEMS + UPDATE STOCK
            ========================= */
            foreach ($items as $item) {
                SaleItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $item['product']->decrement('stock', $item['quantity']);

                InventoryTransaction::create([
                    'product_id' => $item['product']->id,
                    'type' => 'stock_out',
                    'quantity' => $item['quantity'],
                    'remarks' => 'Sold via POS - Receipt: ' . $transaction->receipt_no,
                ]);
            }

            DB::commit();

            return redirect()->route('sales.receipt', $transaction->id)
                ->with('success', 'Sale completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    public function receipt($id)
    {
        $transaction = Transaction::with('items.product', 'user')->findOrFail($id);
        return view('sales.receipt', compact('transaction'));
    }
}