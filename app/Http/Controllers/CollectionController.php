<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection; // Import model Collection
use Illuminate\Support\Facades\Auth; // Penting: Pastikan Auth facade ini ada

class CollectionController extends Controller
{
    /**
     * Show the form for creating a new collection.
     */
    public function create()
    {
        return view('collections.create');
    }

    /**
     * Store a newly created collection in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        // Menggunakan Auth::user() untuk konsistensi akses user
        Auth::user()->collections()->create($validated);

        // Redirect ke halaman "Kartu Saya" setelah berhasil
        return redirect()->route('flashcards.index')->with('status', 'Kategori baru berhasil ditambahkan!');
    }

    /*
    * Method 'index' di bawah ini tidak lagi digunakan
    * karena rute 'collections.index' telah dihapus
    * dan daftar koleksi/kategori sekarang ditampilkan di 'flashcards.index'.
    * Anda bisa menghapusnya sepenuhnya atau biarkan dikomentari.
    */
    /*
    public function index()
    {
        $collections = Auth::user()->collections()->latest()->get();
        return view('collections.index', compact('collections'));
    }
    */

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection)
    {
        // Pastikan koleksi dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $collection->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('collections.edit', compact('collection'));
    }

    /**
     * Update the specified collection in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        // Pastikan koleksi dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $collection->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $collection->update($validated);

        return redirect()->route('flashcards.index')->with('status', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified collection from storage.
     */
    public function destroy(Collection $collection)
    {
        // Pastikan koleksi dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $collection->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $collection->delete(); // Decks yang terkait akan menjadi NULL karena onDelete('SET NULL')

        return redirect()->route('flashcards.index')->with('status', 'Kategori berhasil dihapus!');
    }
}