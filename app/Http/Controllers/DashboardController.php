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
        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('total') ?: 0;
        $yesterdaySales = Transaction::whereDate('created_at', Carbon::yesterday())->sum('total') ?: 0;
        $salesChange = $yesterdaySales > 0 ? (($todaySales - $yesterdaySales) / $yesterdaySales * 100) : 0;

        // Total Pengunjung Hari Ini
        $todayVisitors = Visitor::whereDate('date', Carbon::today())->sum('count') ?: 0;
        $yesterdayVisitors = Visitor::whereDate('date', Carbon::yesterday())->sum('count') ?: 0;
        $visitorsChange = $yesterdayVisitors > 0 ? (($todayVisitors - $yesterdayVisitors) / $yesterdayVisitors * 100) : 0;

        // Total Stok
        $totalStock = Shoe::sum('stock') ?: 0;
        $stockChange = 0;

        // Grafik Penjualan Mingguan
        $weeklySales = [];
        $weeklyTarget = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('l');
            $sales = Transaction::whereDate('created_at', $date)->sum('total') ?: 0;
            $weeklySales[] = $sales;
            $weeklyTarget[] = 1500000;
        }
        // Pastikan data tidak kosong
        if (empty(array_filter($weeklySales)) || empty($labels)) {
            Log::warning('Data grafik penjualan kosong, menggunakan dummy data.');
            $weeklySales = [1000000, 1500000, 2000000, 2500000, 2200000, 1800000, 3000000];
            $weeklyTarget = [1500000, 1500000, 1500000, 1500000, 1500000, 1500000, 1500000];
            $labels = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        }

        // Produk Terlaris
        $topProducts = TransactionItem::selectRaw('barcode, SUM(quantity) as total')
            ->groupBy('barcode')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $shoe = Shoe::where('barcode', $item->barcode)->first();
                return [
                    'name' => $shoe ? $shoe->name : 'Unknown',
                    'total' => $item->total ?: 0,
                ];
            });
        $topProductLabels = $topProducts->pluck('name')->toArray();
        $topProductData = $topProducts->pluck('total')->toArray();
        // Pastikan data tidak kosong
        if (empty(array_filter($topProductData)) || empty($topProductLabels)) {
            Log::warning('Data produk terlaris kosong, menggunakan dummy data.');
            $topProductLabels = ['Nike Air Max', 'Adidas Ultraboost', 'Puma RS-X', 'Converse', 'Nike Air Jordan'];
            $topProductData = [30, 25, 20, 15, 10];
        }

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
                    'product' => $shoe ? $shoe->name . ' (' . $shoe->size . ')' : 'Unknown',
                    'quantity' => $item ? $item->quantity : 1,
                    'total' => $transaction->total ?? ($item ? $item->total : 0),
                    'status' => rand(0, 2) == 0 ? 'Selesai' : (rand(0, 1) ? 'Proses' : 'Pending'),
                ];
            });

        // Debugging
        Log::info('Weekly Sales: ' . json_encode($weeklySales));
        Log::info('Weekly Target: ' . json_encode($weeklyTarget));
        Log::info('Labels: ' . json_encode($labels));
        Log::info('Top Product Labels: ' . json_encode($topProductLabels));
        Log::info('Top Product Data: ' . json_encode($topProductData));

        return view('dashboard', compact(
            'todaySales',
            'salesChange',
            'todayVisitors',
            'visitorsChange',
            'totalStock',
            'stockChange',
            'weeklySales',
            'weeklyTarget',
            'labels',
            'topProductLabels',
            'topProductData',
            'recentTransactions'
        ));
    }
}