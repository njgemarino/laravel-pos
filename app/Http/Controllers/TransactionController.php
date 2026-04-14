<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionController extends Controller
{
    public function index(Request $request)
{
    $query = Transaction::with('user');

    if ($request->search) {
        $query->where('receipt_no', 'like', '%' . $request->search . '%');
    }

    if ($request->date) {
        $query->whereDate('created_at', $request->date);
    }

    $transactions = $query->latest()->get();

    return view('transactions.index', compact('transactions'));
}

    public function show(Transaction $transaction)
    {
        $transaction->load('items.product', 'user');
        return view('transactions.show', compact('transaction'));
    }

    public function export(Request $request)
{
    $query = Transaction::with('user');

    if ($request->search) {
        $query->where('receipt_no', 'like', '%' . $request->search . '%');
    }

    if ($request->date) {
        $query->whereDate('created_at', $request->date);
    }

    $transactions = $query->latest()->get();

    $filename = 'transactions_export_' . now()->format('Ymd_His') . '.csv';

    $response = new StreamedResponse(function () use ($transactions) {
        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'Receipt No',
            'Cashier',
            'Total Amount',
            'Payment',
            'Change',
            'Created At'
        ]);

        foreach ($transactions as $transaction) {
            fputcsv($handle, [
                $transaction->receipt_no,
                $transaction->user->name ?? 'N/A',
                $transaction->total_amount,
                $transaction->payment,
                $transaction->change_amount,
                $transaction->created_at->format('Y-m-d h:i A'),
            ]);
        }

        fclose($handle);
    });

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

    return $response;
}
}