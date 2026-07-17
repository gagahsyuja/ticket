<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi_id' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'tanggal_waktu' => 'required|date|after:now',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tikets' => 'required|array|min:1',
            'tikets.*.tipe' => 'required|in:reguler,premium',
            'tikets.*.harga' => 'required|numeric|min:0',
            'tikets.*.stok' => 'required|integer|min:0',
            'tikets.*.id' => 'nullable|exists:tikets,id',
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul event wajib diisi.',
            'judul.string' => 'Judul event harus berupa teks.',
            'judul.max' => 'Judul event maksimal 255 karakter.',
            'deskripsi.required' => 'Deskripsi event wajib diisi.',
            'deskripsi.string' => 'Deskripsi event harus berupa teks.',
            'lokasi_id.required' => 'Lokasi event wajib diisi.',
            'lokasi_id.string' => 'Lokasi event harus berupa teks.',
            'lokasi_id.max' => 'Lokasi event maksimal 255 karakter.',
            'kategori_id.required' => 'Kategori event wajib dipilih.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'tanggal_waktu.required' => 'Tanggal dan waktu event wajib diisi.',
            'tanggal_waktu.date' => 'Tanggal dan waktu event harus berupa tanggal yang valid.',
            'tanggal_waktu.after' => 'Tanggal dan waktu event harus setelah waktu sekarang.',
            'gambar.image' => 'Gambar harus berupa file gambar.',
            'gambar.mimes' => 'Gambar harus berformat jpg, jpeg, atau png.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'tikets.required' => 'Tiket wajib ditambahkan minimal 1.',
            'tikets.array' => 'Tiket harus berupa array.',
            'tikets.min' => 'Minimal harus ada 1 tiket.',
            'tikets.*.tipe.required' => 'Tipe tiket wajib diisi.',
            'tikets.*.tipe.in' => 'Tipe tiket harus reguler atau premium.',
            'tikets.*.harga.required' => 'Harga tiket wajib diisi.',
            'tikets.*.harga.numeric' => 'Harga tiket harus berupa angka.',
            'tikets.*.harga.min' => 'Harga tiket minimal 0.',
            'tikets.*.stok.required' => 'Stok tiket wajib diisi.',
            'tikets.*.stok.integer' => 'Stok tiket harus berupa bilangan bulat.',
            'tikets.*.stok.min' => 'Stok tiket minimal 0.',
            'tikets.*.id.exists' => 'ID tiket tidak valid.',
        ];
    }
}
