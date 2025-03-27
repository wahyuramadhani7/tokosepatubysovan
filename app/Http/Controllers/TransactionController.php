<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum('total');
        $shoes = Shoe::all(); // Ambil semua data sepatu dari inventory untuk modal "Tambah Manual"

        Log::info('Cart Data on Index: ' . json_encode($cart));
        Log::info('Total on Index: ' . $total);

        return view('transactions.index', compact('cart', 'total', 'shoes'));
    }

    public function addToCart(Request $request)
    {
        $barcode = $request->input('barcode');
        Log::info('Attempting to add product with barcode: ' . $barcode);

        // Coba bersihkan barcode dari karakter spesial yang mungkin terbawa dari QR code
        $barcode = trim($barcode);
        
        // Debug log untuk memastikan barcode yang dikirim
        Log::info('Processed barcode: ' . $barcode);

        // Coba cari sepatu berdasarkan barcode
        $shoe = Shoe::where('barcode', $barcode)->first();

        // Jika tidak ditemukan, coba cari dengan berbagai alternatif
        if (!$shoe) {
            Log::warning('Shoe not found with exact barcode. Trying alternative search methods.');
            
            // Coba cari dengan LIKE query jika barcode mengandung spasi atau karakter khusus
            $shoe = Shoe::where('barcode', 'LIKE', "%{$barcode}%")->first();
            
            // Jika masih tidak ditemukan, dan barcode terlihat seperti URL atau teks panjang,
            // coba ekstrak angka saja (asumsi barcode biasanya angka)
            if (!$shoe && strlen($barcode) > 20) {
                $numericOnly = preg_replace('/[^0-9]/', '', $barcode);
                if (strlen($numericOnly) > 0) {
                    Log::info('Trying with numeric-only: ' . $numericOnly);
                    $shoe = Shoe::where('barcode', 'LIKE', "%{$numericOnly}%")->first();
                }
            }
        }

        // Jika masih tidak ditemukan, tampilkan pesan error
        if (!$shoe) {
            Log::error('Product not found for barcode: ' . $barcode);
            return redirect()->back()->with('error', 'Produk tidak ditemukan! Pastikan QR code atau barcode valid. Kode yang di-scan: ' . $barcode);
        }

        // Cek stok
        if ($shoe->stock <= 0) {
            Log::error('Product out of stock for barcode: ' . $barcode);
            return redirect()->back()->with('error', 'Stok produk habis!');
        }

        // Proses penambahan ke keranjang
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
        Log::info('Product added to cart: ' . json_encode($cart[$shoe->id]));

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

        $total = collect($cart)->sum('total');

        if ($request->amount_paid < $total) {
            return redirect()->back()->with('error', 'Jumlah pembayaran kurang dari total!');
        }

        DB::beginTransaction();
        try {
            $transactionId = 'TRX-' . now()->format('Ymd') . '-' . str_pad(Transaction::count() + 1, 3, '0', STR_PAD_LEFT);
            $transaction = Transaction::create([
                'transaction_id' => $transactionId,
                'subtotal' => $total,
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