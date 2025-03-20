<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;

class ShoeController extends Controller
{
    public function index()
    {
        $shoes = Shoe::paginate(10); // Pagination, 10 item per halaman
        
        Log::info('Data sepatu di index: ', $shoes->toArray());

        foreach ($shoes as $shoe) {
            // QR code mengarah ke detail sepatu
            $shoe->qrCode = QrCode::size(100)->generate(
                route('shoes.qr-detail', $shoe->barcode)
            );
        }
        return view('shoes.index', compact('shoes'));
    }

    public function create()
    {
        return view('shoes.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'size' => 'required|string|max:50',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            do {
                $barcode = 'SHOE' . rand(10000, 99999);
                $exists = Shoe::where('barcode', $barcode)->exists();
            } while ($exists);

            $data['barcode'] = $barcode;

            Log::info('Menyimpan data sepatu: ', $data);
            $shoe = Shoe::create($data);
            Log::info('Data sepatu setelah disimpan: ', $shoe->toArray());

            return redirect()->route('shoes.index')->with('success', 'Sepatu ditambahkan! Barcode: ' . $barcode);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan sepatu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan sepatu: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Shoe $shoe)
    {
        return view('shoes.edit', compact('shoe'));
    }

    public function update(Request $request, Shoe $shoe)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'size' => 'required|string|max:50',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
            ]);

            $shoe->update($data);
            Log::info('Data sepatu setelah diperbarui: ', $shoe->toArray());

            return redirect()->route('shoes.index')->with('success', 'Data sepatu berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui sepatu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui sepatu: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Shoe $shoe)
    {
        try {
            $shoe->delete();
            return redirect()->route('shoes.index')->with('success', 'Data sepatu berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus sepatu: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus sepatu!');
        }
    }

    public function printBarcode(Shoe $shoe)
    {
        Log::info('Data sepatu untuk cetak barcode: ', $shoe->toArray());
        
        $qrCode = QrCode::size(150)->generate(
            route('shoes.qr-detail', $shoe->barcode)
        );
        return view('shoes.print-barcode', compact('shoe', 'qrCode'));
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $shoe = Shoe::where('barcode', $barcode)->first();

        if (!$shoe) {
            return redirect()->back()->with('error', 'Barcode tidak ditemukan!');
        }

        Log::info('Data sepatu dari scan barcode: ', $shoe->toArray());
        return view('shoes.scan-result', compact('shoe'));
    }

    public function showFromBarcode($barcode)
    {
        try {
            $shoe = Shoe::where('barcode', $barcode)->firstOrFail();
            Log::info('Menampilkan detail sepatu dari QR code: ', $shoe->toArray());
            return view('shoes.qr-detail', compact('shoe'));
        } catch (\Exception $e) {
            Log::error('Gagal menampilkan detail sepatu: ' . $e->getMessage());
            return redirect('/')->with('error', 'Sepatu tidak ditemukan!');
        }
    }
}