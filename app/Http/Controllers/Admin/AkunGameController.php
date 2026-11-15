<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GameController;
use App\Models\AkunGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AkunGameController extends Controller
{
    public function create()
    {
        return view('admin.akun.create', [
            'games' => GameController::games(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,terjual',

            'server' => 'nullable|string|max:255',
            'multiple_quantity' => 'nullable|boolean',
            'quantity' => 'nullable|integer|min:1',
            'delivery_method' => 'required|in:coordinated,automatic',
            'account_linked' => 'nullable|boolean',
            'delivery_days' => 'nullable|integer|min:0',
            'delivery_hours' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar_tambahan' => 'nullable|array|max:8',
            'gambar_tambahan.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $validated['multiple_quantity'] = $request->boolean('multiple_quantity');
        $validated['account_linked'] = $request->boolean('account_linked');

        $validated['quantity'] = $validated['multiple_quantity']
            ? ($validated['quantity'] ?? 1)
            : 1;

        $validated['tags'] = $validated['tags'] ?? [];

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('akun-game', 'public');
        }

        $gambarTambahan = [];

        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $file) {
                $gambarTambahan[] = $file->store('akun-game', 'public');
            }
        }

        $validated['gambar_tambahan'] = $gambarTambahan;

        AkunGame::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing berhasil ditambahkan!');
    }

    public function edit(AkunGame $akunGame)
    {
        return view('admin.akun.edit', [
            'akun' => $akunGame,
            'games' => GameController::games(),
        ]);
    }

    public function update(Request $request, AkunGame $akunGame)
    {
        $validated = $request->validate([
            'game' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,terjual',

            'server' => 'nullable|string|max:255',
            'multiple_quantity' => 'nullable|boolean',
            'quantity' => 'nullable|integer|min:1',
            'delivery_method' => 'required|in:coordinated,automatic',
            'account_linked' => 'nullable|boolean',
            'delivery_days' => 'nullable|integer|min:0',
            'delivery_hours' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gambar_tambahan' => 'nullable|array|max:8',
            'gambar_tambahan.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_gambar_tambahan' => 'nullable|array',
            'hapus_gambar_tambahan.*' => 'string',
        ]);

        $validated['multiple_quantity'] = $request->boolean('multiple_quantity');
        $validated['account_linked'] = $request->boolean('account_linked');

        $validated['quantity'] = $validated['multiple_quantity']
            ? ($validated['quantity'] ?? 1)
            : 1;

        $validated['tags'] = $validated['tags'] ?? [];

        if ($request->hasFile('gambar')) {
            $this->hapusGambar($akunGame->gambar);

            $validated['gambar'] = $request->file('gambar')
                ->store('akun-game', 'public');
        }

        $gambarTambahanLama = $akunGame->gambar_tambahan ?? [];
        $yangDihapus = $request->input('hapus_gambar_tambahan', []);

        if (! empty($yangDihapus)) {
            $gambarTambahanLama = array_values(array_filter($gambarTambahanLama, function ($path) use ($yangDihapus) {
                if (in_array($path, $yangDihapus, true)) {
                    $this->hapusGambar($path);

                    return false;
                }

                return true;
            }));
        }

        if ($request->hasFile('gambar_tambahan')) {
            foreach ($request->file('gambar_tambahan') as $file) {
                if (count($gambarTambahanLama) < 7) {
                    $gambarTambahanLama[] = $file->store('akun-game', 'public');
                }
            }
        }

        $validated['gambar_tambahan'] = $gambarTambahanLama;

        $akunGame->update($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing berhasil diperbarui!');
    }

    public function destroy(AkunGame $akunGame)
    {
        if ($akunGame->transaksiAkuns()->exists()) {
            return back()->with(
                'error',
                'Akun sudah memiliki transaksi dan tidak bisa dihapus.'
            );
        }

        $this->hapusGambar($akunGame->gambar);

        foreach ($akunGame->gambar_tambahan ?? [] as $gambar) {
            $this->hapusGambar($gambar);
        }

        $akunGame->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Listing berhasil dihapus!');
    }

    private function hapusGambar(?string $gambar): void
    {
        if ($gambar && str_starts_with($gambar, 'akun-game/')) {
            Storage::disk('public')->delete($gambar);
        }
    }
}
