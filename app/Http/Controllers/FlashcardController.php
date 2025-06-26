<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Deck;
use App\Models\Flashcard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FlashcardController extends Controller
{
    /**
     * Display a listing of the user's collections and their decks.
     */
     public function index(Request $request)
    {
        $query = $request->input('q');
        $searchType = $request->input('type');

        $user = Auth::user();

        // Base queries for collections and uncategorized decks
        $collectionsQuery = $user->collections()
                                 ->with(['decks' => function ($q) use ($user) {
                                     $q->where('user_id', $user->id); // Ensure only user's decks are loaded
                                 }])
                                 ->latest();

        $uncategorizedDecksQuery = $user->decks()
                                        ->whereNull('collection_id')
                                        ->latest();

        // Apply search filters if a query is present
        if ($query) {
            if ($searchType === 'deck') {
                // Filter decks within collections
                $collectionsQuery->whereHas('decks', function ($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%');
                });
                // Filter uncategorized decks
                $uncategorizedDecksQuery->where('title', 'like', '%' . $query . '%')
                                        ->orWhere('description', 'like', '%' . $query . '%');
            } elseif ($searchType === 'kategori') {
                $collectionsQuery->where('name', 'like', '%' . $query . '%');
                // Uncategorized decks don't have a category name to search, so no filter here.
            } elseif ($searchType === 'flashcard') {
                // Filter collections that have decks containing matching flashcards
                $collectionsQuery->whereHas('decks.flashcards', function ($q) use ($query) {
                    $q->where('side_a', 'like', '%' . $query . '%')
                      ->orWhere('side_b', 'like', '%' . $query . '%');
                });
                // Filter uncategorized decks that contain matching flashcards
                $uncategorizedDecksQuery->whereHas('flashcards', function ($q) use ($query) {
                    $q->where('side_a', 'like', '%' . $query . '%')
                      ->orWhere('side_b', 'like', '%' . $query . '%');
                });
            }
        }

        // Get the results
        $collections = $collectionsQuery->get();
        $uncategorizedDecks = $uncategorizedDecksQuery->get();

        // Pass the query and searchType back to the view for sticky form fields
        return view('flashcards.index', compact('collections', 'uncategorizedDecks', 'query', 'searchType'));
    }

    /**
     * Show the form for creating a new flashcard for a specific deck.
     * This method receives the expectedCount parameter to control how many
     * flashcard input fields are shown.
     *
     * @param  \App\Models\Deck  $deck
     * @param  int  $expectedCount
     * @return \Illuminate\View\View
     */
    public function createForDeck(Deck $deck, $expectedCount = 1)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.'); // Atau redirect dengan pesan error
        }

        // Meneruskan objek deck dan nilai expectedCount ke view
        return view('flashcards.create-for-deck', compact('deck', 'expectedCount'));
    }

    /**
     * Store a newly created flashcard(s) in storage for a specific deck.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_for_deck(Request $request, Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'flashcards.*.side_a' => 'required|string|max:1000',
            'flashcards.*.side_b' => 'required|string|max:1000',
        ]);

        foreach ($validatedData['flashcards'] as $flashcardData) {
            $deck->flashcards()->create([
                'side_a' => $flashcardData['side_a'],
                'side_b' => $flashcardData['side_b'],
                'user_id' => Auth::id(), // Pastikan flashcard juga terkait dengan user
            ]);
        }

        return redirect()->route('decks.show', $deck->id)
                         ->with('status', count($validatedData['flashcards']) . ' Flashcard berhasil ditambahkan!');
    }


    public function edit(Flashcard $flashcard)
    {
        if (Auth::user()->id !== $flashcard->user_id) { abort(403, 'Unauthorized action.'); }
        return view('flashcards.edit', compact('flashcard'));
    }

    public function update(Request $request, Flashcard $flashcard)
    {
        if (Auth::user()->id !== $flashcard->user_id) { abort(403, 'Unauthorized action.'); }
        $validated = $request->validate(['side_a' => 'required|string|max:1000', 'side_b' => 'required|string|max:1000',]);
        $flashcard->update($validated);
        return redirect()->route('decks.show', $flashcard->deck_id)->with('status', 'Flashcard berhasil diperbarui!');
    }

    public function destroy(Flashcard $flashcard)
    {
        if (Auth::user()->id !== $flashcard->user_id) { abort(403, 'Unauthorized action.'); }
        $deckId = $flashcard->deck_id;
        $flashcard->delete();
        return redirect()->route('decks.show', $deckId)->with('status', 'Flashcard berhasil dihapus!');
    }

    /**
     * Display the Flips Arena for a specific deck.
     */
    public function flipsArena(Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.'); // Atau redirect dengan pesan error
        }

        // Ambil semua flashcard yang terkait dengan deck ini
        $flashcards = $deck->flashcards()->latest()->get();

        // Jika tidak ada flashcard di deck ini
        if ($flashcards->isEmpty()) {
            return redirect()->route('flashcards.index')->with('error', 'Tidak ada flashcard di deck ini. Silakan tambahkan flashcard terlebih dahulu.');
        }

        return view('flips.arena', compact('deck', 'flashcards'));
    }

    public function createFromJson(Deck $deck)
    {
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('flashcards.create-from-json', compact('deck'));
    }

    /**
     * Store newly created flashcards from JSON for a specific deck.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deck  $deck
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFromJson(Request $request, Deck $deck)
    {
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // 1. Validasi input JSON mentah
        $request->validate([
            'json_data' => 'required|string',
        ]);

        $jsonData = $request->input('json_data');
        $flashcardsData = null;

        // 2. Coba decode JSON
        try {
            $flashcardsData = json_decode($jsonData, true);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'json_parse_error' => 'Data JSON tidak valid. Pastikan formatnya benar.'
            ])->redirectTo(route('flashcards.create_from_json', $deck->id))->withInput();
        }

        // 3. Validasi bahwa hasil decode adalah array
        if (!is_array($flashcardsData)) {
            throw ValidationException::withMessages([
                'flashcards_data_error' => 'Data JSON harus berupa array objek flashcard.'
            ])->redirectTo(route('flashcards.create_from_json', $deck->id))->withInput();
        }

        $createdCount = 0;
        $errors = [];

        // 4. Loop melalui data flashcard dan validasi setiap item
        foreach ($flashcardsData as $index => $flashcardData) {
            // Pastikan setiap item adalah array dan memiliki kunci side_a dan side_b
            if (!is_array($flashcardData) || !isset($flashcardData['side_a']) || !isset($flashcardData['side_b'])) {
                $errors[] = "Flashcard ke-" . ($index + 1) . " tidak memiliki format yang benar (hilang 'side_a' atau 'side_b').";
                continue; // Lanjut ke flashcard berikutnya
            }

            // Validasi string dan panjang
            if (!is_string($flashcardData['side_a']) || empty($flashcardData['side_a']) || strlen($flashcardData['side_a']) > 1000) {
                $errors[] = "Sisi A flashcard ke-" . ($index + 1) . " tidak valid (harus string, tidak kosong, maks 1000 karakter).";
                continue;
            }
            if (!is_string($flashcardData['side_b']) || empty($flashcardData['side_b']) || strlen($flashcardData['side_b']) > 1000) {
                $errors[] = "Sisi B flashcard ke-" . ($index + 1) . " tidak valid (harus string, tidak kosong, maks 1000 karakter).";
                continue;
            }

            // 5. Buat flashcard
            $deck->flashcards()->create([
                'user_id' => Auth::user()->id,
                'side_a' => $flashcardData['side_a'],
                'side_b' => $flashcardData['side_b'],
            ]);
            $createdCount++;
        }

        // Tangani error jika ada
        if (!empty($errors)) {
            $errorMessage = "Beberapa flashcard gagal ditambahkan:<br>" . implode("<br>", $errors);
            // Anda bisa memilih untuk menampilkan error ini di halaman yang sama atau redirect dengan error
            return redirect()->route('decks.show', $deck->id)
                             ->with('error', $errorMessage);
        }

        if ($createdCount > 0) {
            return redirect()->route('decks.show', $deck->id)
                             ->with('status', $createdCount . ' Flashcard berhasil ditambahkan dari JSON!');
        } else {
            return redirect()->route('flashcards.create_from_json', $deck->id)
                             ->with('error', 'Tidak ada flashcard yang berhasil ditambahkan. Pastikan format JSON Anda benar.');
        }
    }

        public function bulkDestroy(Request $request, Deck $deck)
    {
        // Pastikan deck dimiliki oleh user yang sedang login
        if (Auth::user()->id !== $deck->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi bahwa ada ID flashcard yang dikirim
        $request->validate([
            'flashcard_ids' => 'required|array',
            'flashcard_ids.*' => 'exists:flashcards,id', // Pastikan setiap ID flashcard ada
        ]);

        $flashcardIds = $request->input('flashcard_ids');
        $deletedCount = 0;

        // Hapus flashcard satu per satu, pastikan setiap flashcard dimiliki oleh user dan deck yang benar
        foreach ($flashcardIds as $flashcardId) {
            $flashcard = Flashcard::where('id', $flashcardId)
                                  ->where('deck_id', $deck->id)
                                  ->where('user_id', Auth::id())
                                  ->first();

            if ($flashcard) {
                $flashcard->delete();
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            return redirect()->route('decks.show', $deck->id)->with('status', $deletedCount . ' Flashcard berhasil dihapus.');
        } else {
            return redirect()->route('decks.show', $deck->id)->with('error', 'Tidak ada flashcard yang valid ditemukan atau dihapus.');
        }
    }

      public function storeForDeck(Request $request, Deck $deck)
    {
        // 1. Validasi Input
        $request->validate([
            'flashcards.*.side_a' => 'required|string|max:255',
            'flashcards.*.image_a' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
            'flashcards.*.side_b' => 'required|string|max:255',
            'flashcards.*.image_b' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
        ]);

        foreach ($request->flashcards as $i => $flashcardData) { // Gunakan $i untuk mengakses file input
            $imageAPath = null;
            $imageBPath = null;

            // Tentukan dimensi target untuk resize (sesuaikan ini!)
            // Misalnya, flashcard Anda punya dimensi sekitar 400px x 300px
            $targetWidth = 600;  // Ukuran width yang wajar untuk display, bisa disesuaikan
            $targetHeight = 450; // Ukuran height yang wajar untuk display, bisa disesuaikan
                                 // Pertimbangkan rasio aspek asli jika ingin menjaga proporsi.

            // 2. Proses Unggah dan Resize Gambar untuk Sisi A
            if ($request->hasFile("flashcards.{$i}.image_a")) {
                $image = $request->file("flashcards.{$i}.image_a");
                $filename = time() . '_sideA_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Buat instance Image dari Intervention Image
                $img = Image::make($image);

                // Resize gambar:
                // Mengubah ukuran gambar dengan mempertahankan rasio aspek
                // dan memastikan tidak melebihi target width/height
                $img->resize($targetWidth, $targetHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Hanya mengecilkan jika lebih besar dari target
                });

                // Simpan gambar ke storage/app/public/flashcard_images
                // Intervention Image bisa langsung menyimpan ke disk Laravel Storage
                Storage::disk('public')->put('flashcard_images/' . $filename, (string) $img->encode());
                $imageAPath = 'flashcard_images/' . $filename; // Simpan path relatif
            }

            // 3. Proses Unggah dan Resize Gambar untuk Sisi B
            if ($request->hasFile("flashcards.{$i}.image_b")) {
                $image = $request->file("flashcards.{$i}.image_b");
                $filename = time() . '_sideB_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $img = Image::make($image);
                $img->resize($targetWidth, $targetHeight, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::disk('public')->put('flashcard_images/' . $filename, (string) $img->encode());
                $imageBPath = 'flashcard_images/' . $filename;
            }

            // 4. Simpan Data ke Database
            Flashcard::create([
                'deck_id' => $deck->id,
                'side_a' => $flashcardData['side_a'],
                'image_a' => $imageAPath,
                'side_b' => $flashcardData['side_b'],
                'image_b' => $imageBPath,
            ]);
        }

        return redirect()->route('decks.show', $deck->id)->with('status', 'Flashcard(s) berhasil ditambahkan!');
    }
    
    
}
