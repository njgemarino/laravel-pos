<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $todaySales = Transaction::whereDate('created_at', today())->sum('total_amount');

        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        $bestSellingProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $monthlySales = Transaction::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalTransactions',
            'todaySales',
            'lowStockProducts',
            'recentTransactions',
            'bestSellingProducts',
            'monthlySales'
        ));
    }
}