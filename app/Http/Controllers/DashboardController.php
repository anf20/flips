<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flashcard; // Pastikan model Flashcard diimpor
use App\Models\Deck;      // Pastikan model Deck diimpor
use App\Models\Collection; // Pastikan model Collection diimpor
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user's dashboard with counts of flashcards, decks, and collections.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil jumlah total flashcard, deck, dan koleksi milik pengguna yang login
        $totalFlashcards = $user->flashcards()->count();
        $totalDecks = $user->decks()->count();
        $totalCollections = $user->collections()->count();

        // Kirim data ini ke view dashboard
        return view('dashboard', compact('totalFlashcards', 'totalDecks', 'totalCollections'));
    }
}
