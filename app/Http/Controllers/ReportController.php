<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\SaleItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function dailySales(Request $request)
{
    $date = $request->date ?? now()->toDateString();

    $transactions = Transaction::with('user')
        ->whereDate('created_at', $date)
        ->latest()
        ->get();

    $totalSales = $transactions->sum('total_amount');
    $totalTransactions = $transactions->count();
    $itemsSold = SaleItem::whereDate('created_at', $date)->sum('quantity');
    $averageSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

    return view('reports.daily-sales', compact(
        'transactions',
        'totalSales',
        'totalTransactions',
        'itemsSold',
        'averageSale',
        'date'
    ));
}

public function exportDailySales(Request $request)
{
    $date = $request->date ?? now()->toDateString();

    $transactions = Transaction::with('user')
        ->whereDate('created_at', $date)
        ->latest()
        ->get();

    $filename = 'daily_sales_report_' . $date . '.csv';

    $response = new StreamedResponse(function () use ($transactions) {
        $handle = fopen('php://output', 'w');

        fputcsv($handle, [
            'Receipt No',
            'Cashier',
            'Total Amount',
            'Payment',
            'Change',
            'Date/Time'
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

public function monthlySales(Request $request)
{
    $month = $request->month ?? now()->format('Y-m');

    $transactions = \App\Models\Transaction::with('user')
        ->whereYear('created_at', substr($month, 0, 4))
        ->whereMonth('created_at', substr($month, 5, 2))
        ->latest()
        ->get();

    $totalSales = $transactions->sum('total_amount');
    $totalTransactions = $transactions->count();
    $averageSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;
    $itemsSold = \App\Models\SaleItem::whereYear('created_at', substr($month, 0, 4))
        ->whereMonth('created_at', substr($month, 5, 2))
        ->sum('quantity');

    return view('reports.monthly-sales', compact(
        'transactions',
        'totalSales',
        'totalTransactions',
        'averageSale',
        'itemsSold',
        'month'
    ));
}

}