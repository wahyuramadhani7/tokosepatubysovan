<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Penjualan Hari Ini
        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total') ?? 0;
        $yesterdaySales = Transaction::whereDate('created_at', Carbon::yesterday())->sum('total') ?? 0;
        $salesChange = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales * 100) : 0;

        // Total Pengunjung Hari Ini
        $todayVisitors = Visitor::whereDate('date', Carbon::today())->sum('count') ?? 0;
        $yesterdayVisitors = Visitor::whereDate('date', Carbon::yesterday())->sum('count') ?? 0;
        $visitorsChange = $yesterdayVisitors > 0 ? (($todayVisitors - $yesterdayVisitors) / $yesterdayVisitors * 100) : 0;

        // Total Stok
        $totalStock = Shoe::sum('stock') ?? 0;
        $stockChange = 0;

        // Transaksi Terbaru
        $recentTransactions = Transaction::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($transaction) {
                $item = $transaction->items()->first();
                $shoe = $item ? Shoe::where('barcode', $item->barcode)->first() : null;
                return [
                    'id' => 'TRX-' . str_pad($transaction->id, 10, '0', STR_PAD_LEFT),
                    'customer' => $transaction->customer_name ?? 'Pelanggan-' . rand(100, 999),
                    'product' => $shoe ? $shoe->name . ' (' . $shoe->size . ')' : 'Tidak Dikenal',
                    'quantity' => $item ? $item->quantity : 1,
                    'total' => $transaction->total ?? ($item ? $item->total : 0),
                    'status' => rand(0, 2) == 0 ? 'Selesai' : (rand(0, 1) ? 'Proses' : 'Pending'),
                ];
            });

        return view('dashboard', compact(
            'todaySales',
            'salesChange',
            'todayVisitors',
            'visitorsChange',
            'totalStock',
            'stockChange',
            'recentTransactions'
        ));
    }
}