<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Deck;
use App\Models\Flashcard; // Pastikan Flashcard Model diimpor jika digunakan di sini (contoh: untuk destroy)
use Illuminate\Validation\ValidationException; // Jika ValidationException spesifik dibutuhkan
use Illuminate\Support\Facades\Auth;

class DeckController extends Controller
{
    /**
     * Show the form for creating a new deck.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Ambil semua koleksi (kategori) yang dimiliki user untuk dropdown
        $collections = Auth::user()->collections()->get(['id', 'name']);
        return view('decks.create', compact('collections'));
    }

    /**
     * Store a newly created deck in storage and redirect to flashcard creation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'collection_id' => 'nullable|exists:collections,id',
            'expected_flashcards_count' => 'required|integer|min:1|max:100', // Validasi jumlah flashcard
        ]);

        // --- Logika Kategori "Baru Ditambahkan" dari kode Anda ---
        $collectionIdToUse = $validated['collection_id'];

        if (is_null($collectionIdToUse)) {
            $defaultCollection = Auth::user()->collections()->firstOrCreate(
                ['name' => 'Baru Ditambahkan'],
                ['description' => 'Kategori otomatis untuk deck yang baru ditambahkan.']
            );
            $collectionIdToUse = $defaultCollection->id;
        }

        // Buat Deck baru
        $deck = Auth::user()->decks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'collection_id' => $collectionIdToUse,
            // Simpan juga expected_flashcards_count ke dalam model Deck jika Anda memiliki kolomnya di tabel decks
            // Jika tidak ada kolom ini di tabel decks, baris ini harus dihapus atau diabaikan
            'expected_flashcards_count' => $validated['expected_flashcards_count'],
        ]);

        // Redirect ke halaman tambah flashcard dengan membawa ID deck dan expected_flashcards_count
        // Ini adalah bagian penting yang memastikan jumlah flashcard sesuai input
        return redirect()->route('flashcards.create_for_deck', [
            'deck' => $deck->id,
            'expectedCount' => $validated['expected_flashcards_count']
        ])->with('status', 'Deck "' . $deck->title . '" berhasil dibuat! Sekarang tambahkan flashcard.');
    }

    /**
     * Display the specified deck and its flashcards.
     *
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\View\View
     */
    public function show(Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.'); // Atau redirect dengan pesan error
        }

        // Ambil semua flashcard yang terkait dengan deck ini
        $flashcards = $deck->flashcards()->latest()->get();

        return view('decks.show', compact('deck', 'flashcards'));
    }

    /**
     * Show the form for editing the specified deck.
     *
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\View\View
     */
    public function edit(Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua koleksi (kategori) yang dimiliki user untuk dropdown
        $collections = Auth::user()->collections()->get(['id', 'name']);

        return view('decks.edit', compact('deck', 'collections'));
    }

    /**
     * Update the specified deck in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'collection_id' => 'nullable|exists:collections,id',
        ]);

        // Jika collection_id dikirim sebagai null, set ke null di database
        if (empty($validated['collection_id'])) {
            $validated['collection_id'] = null;
        }

        $deck->update($validated);

        return redirect()->route('decks.show', $deck->id)->with('status', 'Deck berhasil diperbarui!');
    }

    /**
     * Remove the specified deck from storage.
     *
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Karena Anda menyebutkan onDelete('cascade') di migrasi, cukup panggil delete() pada deck.
        // Ini akan otomatis menghapus flashcard terkait.
        $deck->delete(); 

        return redirect()->route('flashcards.index')->with('status', 'Deck berhasil dihapus!');
    }

    // PENTING: Metode createFlashcardsForDeck dan storeFlashcardsForDeck
    // SEHARUSNYA BERADA DI FlashcardController, bukan di DeckController.
    // Pastikan Anda telah memindahkan kedua metode ini ke FlashcardController
    // seperti yang disarankan dalam interaksi sebelumnya, dan rute di web.php
    // menunjuk ke FlashcardController untuk tindakan tersebut.
    /*
    public function createFlashcardsForDeck(Deck $deck) { ... }
    public function storeFlashcardsForDeck(Request $request, Deck $deck) { ... }
    */
}
