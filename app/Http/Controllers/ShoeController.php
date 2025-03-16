<?php

namespace App\Http\Controllers;

use App\Models\Shoe;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShoeController extends Controller
{
    public function index()
    {
        $shoes = Shoe::paginate(10); // Pagination, 10 item per halaman
        foreach ($shoes as $shoe) {
            $shoe->qrCode = QrCode::size(100)->generate($shoe->barcode); // Tambahkan QR Code ke objek shoe
        }
        return view('shoes.index', compact('shoes'));
    }

    public function create()
    {
        return view('shoes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'size' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        do {
            $barcode = 'SHOE' . rand(10000, 99999);
            $exists = Shoe::where('barcode', $barcode)->exists();
        } while ($exists);

        $data['barcode'] = $barcode;
        Shoe::create($data);
        return redirect()->route('shoes.index')->with('success', 'Sepatu ditambahkan! Barcode: ' . $barcode);
    }

    public function edit(Shoe $shoe)
    {
        return view('shoes.edit', compact('shoe'));
    }

    public function update(Request $request, Shoe $shoe)
    {
        $data = $request->validate([
            'name' => 'required',
            'size' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        $shoe->update($data);
        return redirect()->route('shoes.index')->with('success', 'Data sepatu berhasil diperbarui!');
    }

    public function destroy(Shoe $shoe)
    {
        $shoe->delete();
        return redirect()->route('shoes.index')->with('success', 'Data sepatu berhasil dihapus!');
    }

    public function printBarcode(Shoe $shoe)
    {
        $qrCode = QrCode::size(150)->generate($shoe->barcode);
        return view('shoes.print-barcode', compact('shoe', 'qrCode'));
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->input('barcode');
        $shoe = Shoe::where('barcode', $barcode)->first();

        if (!$shoe) {
            return redirect()->back()->with('error', 'Barcode tidak ditemukan!');
        }

        return view('shoes.scan-result', compact('shoe'));
    }
}