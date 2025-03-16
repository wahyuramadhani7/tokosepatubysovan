<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum('total'); // Hanya total, tanpa ppn

        return view('transactions.index', compact('cart', 'total')); // Hapus subtotal dan ppn
    }

    public function addToCart(Request $request)
    {
        $barcode = $request->input('barcode');
        $shoe = Shoe::where('barcode', $barcode)->first();

        if (!$shoe) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        if ($shoe->stock <= 0) {
            return redirect()->back()->with('error', 'Stok produk habis!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$shoe->id])) {
            $cart[$shoe->id]['quantity']++;
            $cart[$shoe->id]['total'] = $cart[$shoe->id]['quantity'] * $shoe->price;
        } else {
            $cart[$shoe->id] = [
                'barcode' => $shoe->barcode,
                'name' => $shoe->name,
                'price' => $shoe->price,
                'quantity' => 1,
                'total' => $shoe->price,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('transactions.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function manualAdd(Request $request)
    {
        $shoeId = $request->input('shoe_id');
        $shoe = Shoe::find($shoeId);

        if (!$shoe) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        if ($shoe->stock <= 0) {
            return redirect()->back()->with('error', 'Stok produk habis!');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$shoe->id])) {
            $cart[$shoe->id]['quantity']++;
            $cart[$shoe->id]['total'] = $cart[$shoe->id]['quantity'] * $shoe->price;
        } else {
            $cart[$shoe->id] = [
                'barcode' => $shoe->barcode,
                'name' => $shoe->name,
                'price' => $shoe->price,
                'quantity' => 1,
                'total' => $shoe->price,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('transactions.index')->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang kosong!');
        }

        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|in:tunai,debit_kredit,qris',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $total = collect($cart)->sum('total'); // Hanya total, tanpa ppn

        if ($request->amount_paid < $total) {
            return redirect()->back()->with('error', 'Jumlah pembayaran kurang dari total!');
        }

        DB::beginTransaction();
        try {
            $transactionId = 'TRX-' . now()->format('Ymd') . '-' . str_pad(Transaction::count() + 1, 3, '0', STR_PAD_LEFT);
            $transaction = Transaction::create([
                'transaction_id' => $transactionId,
                'subtotal' => $total, // Subtotal sama dengan total karena tidak ada ppn
                'total' => $total,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'change' => $request->amount_paid - $total,
            ]);

            foreach ($cart as $shoeId => $item) {
                $shoe = Shoe::find($shoeId);
                $shoe->decrement('stock', $item['quantity']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'shoe_id' => $shoeId,
                    'barcode' => $item['barcode'],
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);
            }

            DB::commit();
            session()->forget('cart');
            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil! ID Transaksi: ' . $transactionId);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        session()->forget('cart');
        return redirect()->route('transactions.index')->with('success', 'Transaksi dibatalkan.');
    }
}