<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketJoki;
use Illuminate\Http\Request;

class PaketJokiController extends Controller
{
    public function store(Request $request)
    {
        $request->merge(['harga' => preg_replace('/\D/', '', (string) $request->input('harga'))]);

        $validated = $request->validate([
            'game' => 'required|string|max:255',
            'nama_paket' => 'required|string|max:255',
            'kategori' => 'nullable|in:'.implode(',', array_keys(PaketJoki::KATEGORI)),
            'harga' => 'required|integer|min:0',
            'estimasi_waktu' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        PaketJoki::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Paket joki berhasil ditambahkan!');
    }
}
