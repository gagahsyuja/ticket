<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Lokasi::where('aktif', '=', '1');


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%")
                    ->andWhere('aktif', '=', "1");
            });
        }

        $sort = $request->get('sort', 'asc');

        $lokasis = $query->paginate(10);

        return view('pages.admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        $lokasis = Lokasi::all();

        return view('pages.admin.lokasi.create', compact('lokasis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255'
        ]);

        $record = Lokasi::where('nama_lokasi', '=', $request->nama_lokasi);

        if ($record && $record->value('aktif') == '0') {
            $record->update([
                'aktif' => '1'
            ]);
        } else if ($record && $record->value('aktif') == '1') {
            return back()->with('error', "Lokasi telah ada!");
        } else {
            Lokasi::create([
                'nama_lokasi' => $request->nama_lokasi
            ]);
        }


        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('pages.admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255|unique:lokasis,nama_lokasi,' . $id
        ]);

        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update([
            'nama_lokasi' => $request->nama_lokasi
        ]);

        return redirect()->route('lokasi.index')
            ->with('success', 'Lokasi berhasil diperbarui!');
    }

    public function destroy(Lokasi $lokasi)
    {
        if ($lokasi->hasEvent()) {
            return back()->with('error', 'Tidak dapat menghapus lokasi yang sudah memiliki event.');
        }

        $lokasi = Lokasi::findOrFail($lokasi->id);
        $lokasi->update([
            'aktif' => '0'
        ]);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
