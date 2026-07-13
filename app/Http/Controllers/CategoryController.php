<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama'
        ]);

        Kategori::create([
            'nama' => $request->nama
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $id
        ]);

        $category = Kategori::findOrFail($id);
        $category->update([
            'nama' => $request->nama
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Kategori::findOrFail($id);
        $category->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
