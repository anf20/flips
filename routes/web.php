<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk halaman selamat datang (welcome page)
Route::get('/', function () {
    return view('welcome');
});

// Grup Rute yang memerlukan autentikasi (Login) dan verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {

    // Rute untuk Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

    // Rute untuk Halaman "Kartu Saya" (daftar deck & kategori)
    Route::get('/my-flashcards', [FlashcardController::class, 'index'])->name('flashcards.index');

    // --- Rute untuk Collections (Kategori) ---
    Route::get('/collections/create', [CollectionController::class, 'create'])->name('collections.create');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::get('/collections/{collection}/edit', [CollectionController::class, 'edit'])->name('collections.edit');
    Route::patch('/collections/{collection}', [CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{collection}', [CollectionController::class, 'destroy'])->name('collections.destroy');

    // --- Rute untuk Decks (Judul Kumpulan Flashcard) ---
    Route::get('/decks/create', [DeckController::class, 'create'])->name('decks.create');
    Route::post('/decks', [DeckController::class, 'store'])->name('decks.store');
    Route::get('/decks/{deck}', [DeckController::class, 'show'])->name('decks.show');
    Route::get('/decks/{deck}/edit', [DeckController::class, 'edit'])->name('decks.edit');
    Route::patch('/decks/{deck}', [DeckController::class, 'update'])->name('decks.update');
    Route::delete('/decks/{deck}', [DeckController::class, 'destroy'])->name('decks.destroy');

    // --- Rute untuk Penambahan Flashcard Biasa (via form) ---
    Route::get('/decks/{deck}/flashcards/create/{expectedCount?}', [FlashcardController::class, 'createForDeck'])->name('flashcards.create_for_deck');
    Route::post('/decks/{deck}/flashcards', [FlashcardController::class, 'store_for_deck'])->name('flashcards.store_for_deck');

    // --- Rute untuk Penambahan Flashcard dari JSON ---
    Route::get('/decks/{deck}/flashcards/create-from-json', [FlashcardController::class, 'createFromJson'])->name('flashcards.create_from_json');
    Route::post('/decks/{deck}/flashcards/store-from-json', [FlashcardController::class, 'storeFromJson'])->name('flashcards.store_from_json');
    
    // --- RUTE BARU UNTUK PENGHAPUSAN MASSAL FLASHCARD ---
    Route::delete('/decks/{deck}/flashcards/bulk-destroy', [FlashcardController::class, 'bulkDestroy'])->name('flashcards.bulk_destroy');
    // --- AKHIR RUTE BARU ---

    // --- Rute untuk FLIPS ARENA ---
    Route::get('/decks/{deck}/flips-arena', [FlashcardController::class, 'flipsArena'])->name('flips.arena');

    // --- Rute untuk Profil Pengguna (bawaan Laravel Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rute untuk Flashcard Individu (Edit/Update/Delete) ---
    Route::get('/flashcards/{flashcard}/edit', [FlashcardController::class, 'edit'])->name('flashcards.edit');
    Route::patch('/flashcards/{flashcard}', [FlashcardController::class, 'update'])->name('flashcards.update');
    Route::delete('/flashcards/{flashcard}', [FlashcardController::class, 'destroy'])->name('flashcards.destroy');
});

// Rute autentikasi Breeze (login, register, reset password, dll.)
require __DIR__.'/auth.php';
