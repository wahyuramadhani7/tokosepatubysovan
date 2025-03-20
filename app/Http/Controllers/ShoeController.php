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
        
        // Debugging: Log data yang diambil dari database
        Log::info('Data sepatu di index: ', $shoes->toArray());

        foreach ($shoes as $shoe) {
            // Pastikan barcode ada sebelum generate QR Code
            $shoe->qrCode = QrCode::size(100)->generate($shoe->barcode ?? 'N/A');
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

            // Generate barcode unik
            do {
                $barcode = 'SHOE' . rand(10000, 99999);
                $exists = Shoe::where('barcode', $barcode)->exists();
            } while ($exists);

            $data['barcode'] = $barcode;

            // Log data sebelum disimpan untuk debugging
            Log::info('Menyimpan data sepatu: ', $data);

            // Simpan ke database dan ambil instance yang baru dibuat
            $shoe = Shoe::create($data);

            // Debugging: Log data setelah disimpan
            Log::info('Data sepatu setelah disimpan: ', $shoe->toArray());

            return redirect()->route('shoes.index')->with('success', 'Sepatu ditambahkan! Barcode: ' . $barcode);
        } catch (\Exception $e) {
            // Log error untuk debugging
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

            // Debugging: Log data setelah diperbarui
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
        // Debugging: Log data sepatu untuk cetak barcode
        Log::info('Data sepatu untuk cetak barcode: ', $shoe->toArray());

        $qrCode = QrCode::size(150)->generate($shoe->barcode ?? 'N/A');
        return view('shoes.print-barcode', compact('shoe', 'qrCode'));
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $shoe = Shoe::where('barcode', $barcode)->first();

        if (!$shoe) {
            return redirect()->back()->with('error', 'Barcode tidak ditemukan!');
        }

        // Debugging: Log data sepatu yang ditemukan
        Log::info('Data sepatu dari scan barcode: ', $shoe->toArray());

        return view('shoes.scan-result', compact('shoe'));
    }
}