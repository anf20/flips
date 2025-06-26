@extends('layouts.flips-arena-layout') {{-- Sekarang memperluas layout yang dimodifikasi --}}

@section('content') {{-- Konten ini akan masuk ke @yield('content') di layout parent (flips-arena-layout) --}}

    {{-- Container Layout Utama: Flex untuk desktop (md:flex-row), Stacked for mobile (flex-col) --}}
    <div class="flex flex-col md:flex-row gap-8 items-start md:items-stretch">

        {{-- Bagian 2: Navigasi Flashcard (Kiri di Desktop, Bawah di Mobile) --}}
        <div class="w-full md:w-1/3 bg-white p-6 rounded-lg shadow-xl flex-shrink-0 order-2 md:order-1">
            <div id="shuffle-notification" class="hidden bg-green-100 text-green-700 p-3 rounded-md text-sm text-center mb-4">
                Kartu berhasil diacak!
            </div>
            {{-- Kontrol di atas Navigasi Kartu (Shuffle & Toggle) --}}
            <div class="flex flex-col items-center gap-4 mb-6 w-full">
                <div class="flex flex-wrap items-center justify-center gap-2"> {{-- Container untuk tombol Acak dan indikator --}}
                    
                    <button id="shuffle-btn" class="px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        Acak Kartu
                    </button>
                    <span id="shuffled-indicator" class="text-sm font-semibold text-green-600 hidden">
                        (Teracak!)
                    </span>
                </div>

                {{-- Toggle untuk Tampilkan Sisi B Dulu --}}
                {{-- TOMBOL BARU untuk "Ubah Awal Kartu" --}}
            <button id="toggle-side-btn" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                Ubah Awal Kartu
            </button>
            <span id="initial-side-indicator" class="text-sm text-gray-500 mt-2">
                Kartu akan terbuka dari sisi: **Merah (Depan)**
            </span>
            {{-- Akhir Tombol Baru --}}
            </div>

             {{-- Indikator Awal Kartu Terbuka BARU --}}
            
            {{-- Akhir Indikator BARU --}}


            {{-- Notifikasi setelah diacak --}}
            
            
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Navigasi Kartu</h3>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-md text-center">
                    <p class="text-sm text-gray-600">Kartu Saat Ini</p>
                    <p id="current-card-display" class="text-3xl font-bold text-blue-700">1</p>
                </div>
                <div class="bg-indigo-50 p-4 rounded-md text-center">
                    <p class="text-sm text-gray-600">Total Kartu</p>
                    <p id="total-cards-display" class="text-3xl font-bold text-indigo-700">{{ $flashcards->count() ?? 0 }}</p>
                </div>
            </div>
            
            {{-- Daftar Flashcard (opsional, untuk tinjauan navigasi visual) --}}
            <h4 class="text-lg font-semibold text-gray-700 mb-3">Daftar Kartu</h4>
            <div id="flashcard-list-overview" class="grid grid-cols-5 gap-2 max-h-[300px] overflow-y-auto pr-2 hide-scrollbar">
                {{-- Item flashcard akan dirender di sini by JavaScript --}}
            </div>
        </div>

        {{-- Bagian 1: Flips Arena (Kanan di Desktop, Atas di Mobile) --}}
        <div class="w-full md:w-2/3 bg-white p-6 rounded-lg shadow-xl flex flex-col items-center justify-center order-1 md:order-2">
            
            {{-- Flashcard Container --}}
            <div id="flashcard-container" class="flip-card cursor-pointer mb-6 relative w-full max-w-lg h-80 sm:h-96 md:h-[400px]">
                <div id="flashcard-inner" class="flip-card-inner">
                    <div id="side-a" class="flip-card-front flex items-center justify-center text-center p-6 text-2xl font-bold text-gray-800"></div>
                    <div id="side-b" class="flip-card-back flex items-center justify-center text-center p-6 text-2xl font-bold text-gray-800"></div>
                </div>
                {{-- Loading spinner untuk transisi --}}
                <div id="card-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10 hidden">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                </div>
            </div>

            <div class="flex flex-wrap justify-center gap-4 mb-8">
                <button id="prev-card-btn" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    &larr; Kartu Sebelumnya
                </button>
                <button id="next-card-btn" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Kartu Selanjutnya &rarr;
                </button>
            </div>

            <a href="{{ route('decks.show', $deck->id) }}" class="px-6 py-3 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 text-base font-medium">
                Lihat Detail Deck
            </a>
        </div>
    </div>



    {{-- Gaya for scrollbar hiding dan warna kartu --}}
    @push('styles')
    <style>
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Styling for toggle switch */
        .sr-only { /* Hidden but accessible for screen readers */
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
        .dot {
            /* Pastikan posisi awal dot konsisten dengan 'left-1' (0.25rem) dari Tailwind */
            left: 0.25rem; 
            top: 0.25rem; 
        }
        input:checked + .block {
            background-color: #48bb78; /* Tailwind green-500 */
        }
        input:checked + .block + .dot {
            /* Kalkulasi untuk pergeseran dot yang presisi:
               Lebar total block (w-14) = 56px
               Lebar dot (w-6) = 24px
               Padding kiri/kanan (left-1) = 4px
               Jarak pergeseran = (Lebar block - Lebar dot - 2 * padding)
                              = 56px - 24px - 2*4px = 56px - 24px - 8px = 24px
               Dalam rem: 24px / 16px/rem = 1.5rem
            */
            transform: translateX(1.5rem); 
            background-color: #ffffff; /* White dot when checked */
        }
        .dot {
            transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }

         .flip-card-front {
        background-color: #fecaca; /* Tailwind red-200, lebih pekat dari red-100 */
        color: #991b1b; /* Tailwind red-800, lebih gelap dari red-700 */
        border: 2px solid #ef4444; /* Tambahkan border merah untuk definisi */
    }

    .flip-card-back {
        background-color: #bbf7d0; /* Tailwind green-200, lebih pekat dari green-100 */
        color: #166534; /* Tailwind green-800, lebih gelap dari green-700 */
        transform: rotateY(180deg);
        border: 2px solid #22c55e; /* Tambahkan border hijau untuk definisi */
    }
    </style>
    @endpush

@endsection {{-- End of section content --}}

---

@push('scripts')
@push('scripts')
@push('scripts')
<script>
    const initialFlashcards = @json($flashcards->map(function($card) {
        return ['id' => $card->id, 'sideA' => $card->side_a, 'sideB' => $card->side_b];
    }));

    let flashcards = [...initialFlashcards];
    let currentCardIndex = 0;
    let isShuffled = false;

    const flashcardContainer = document.getElementById('flashcard-container');
    const sideAElement = document.getElementById('side-a');
    const sideBElement = document.getElementById('side-b');
    const prevButton = document.getElementById('prev-card-btn');
    const nextButton = document.getElementById('next-card-btn');
    const shuffleButton = document.getElementById('shuffle-btn');
    const cardLoading = document.getElementById('card-loading');
    
    // UBAH: Referensi ke tombol baru
    const toggleSideButton = document.getElementById('toggle-side-btn'); 
    
    const shuffleNotification = document.getElementById('shuffle-notification');
    const shuffledIndicator = document.getElementById('shuffled-indicator');

    const initialSideIndicator = document.getElementById('initial-side-indicator');

    const currentCardDisplay = document.getElementById('current-card-display');
    const totalCardsDisplay = document.getElementById('total-cards-display');
    const flashcardListOverview = document.getElementById('flashcard-list-overview');

    let displaySideBFirst = localStorage.getItem('displaySideBFirst') === 'true';

    flashcardContainer.addEventListener('click', flipCard);

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }

    function updateInitialSideIndicator() {
        if (displaySideBFirst) {
            initialSideIndicator.innerHTML = 'Kartu akan terbuka dari sisi: <strong>Hijau (Belakang)</strong>';
        } else {
            initialSideIndicator.innerHTML = 'Kartu akan terbuka dari sisi: <strong>Merah (Depan)</strong>';
        }
    }

    function renderCard() {
        cardLoading.classList.add('hidden');

        if (flashcards.length === 0) {
            sideAElement.textContent = "Tidak ada flashcard di deck ini.";
            sideBElement.textContent = "Silakan tambahkan flashcard melalui halaman detail deck.";
            prevButton.disabled = true;
            nextButton.disabled = true;
            shuffleButton.disabled = true;
            
            // UBAH: Nonaktifkan tombol baru jika tidak ada kartu
            toggleSideButton.disabled = true;
            toggleSideButton.classList.add('opacity-50', 'cursor-not-allowed');

            flashcardContainer.classList.add('cursor-not-allowed');
            flashcardContainer.classList.remove('flipped');
            
            currentCardDisplay.textContent = 0;
            totalCardsDisplay.textContent = 0;
            flashcardListOverview.innerHTML = '<p class="text-gray-500 text-sm text-center">Tidak ada kartu.</p>';
            
            updateInitialSideIndicator();
            return;
        }

        const currentCard = flashcards[currentCardIndex];
        sideAElement.textContent = currentCard.sideA;
        sideBElement.textContent = currentCard.sideB;

        flashcardContainer.classList.remove('flipped');
        if (displaySideBFirst) {
            flashcardContainer.classList.add('flipped');
        }
        
        prevButton.disabled = currentCardIndex === 0;
        nextButton.disabled = currentCardIndex === flashcards.length - 1;
        shuffleButton.disabled = flashcards.length <= 1;
        
        // UBAH: Aktifkan tombol baru jika ada kartu
        toggleSideButton.disabled = false; 
        toggleSideButton.classList.remove('opacity-50', 'cursor-not-allowed');

        prevButton.classList.toggle('opacity-50', prevButton.disabled);
        prevButton.classList.toggle('cursor-not-allowed', prevButton.disabled);
        nextButton.classList.toggle('opacity-50', nextButton.disabled);
        nextButton.classList.toggle('cursor-not-allowed', nextButton.disabled);
        shuffleButton.classList.toggle('opacity-50', shuffleButton.disabled);
        shuffleButton.classList.toggle('cursor-not-allowed', shuffleButton.disabled);

        currentCardDisplay.textContent = currentCardIndex + 1;
        totalCardsDisplay.textContent = flashcards.length;
        updateFlashcardListOverview();
        updateInitialSideIndicator();

        if (!isShuffled) {
            shuffledIndicator.classList.add('hidden');
        }
    }

    function flipCard() {
        if (flashcards.length > 0) {
            flashcardContainer.classList.toggle('flipped');
        }
    }

    function transitionCard(updateIndexCallback) {
        if (flashcards.length === 0) return;

        cardLoading.classList.remove('hidden');
        flashcardContainer.classList.remove('flipped'); 

        setTimeout(() => {
            updateIndexCallback();
            renderCard();
        }, 300);
    }

    function nextCard() {
        if (currentCardIndex < flashcards.length - 1) {
            transitionCard(() => currentCardIndex++);
        }
    }

    function prevCard() {
        if (currentCardIndex > 0) {
            transitionCard(() => currentCardIndex--);
        }
    }
    
    function shuffleFlashcards() {
        if (flashcards.length > 1) {
            shuffleArray(flashcards);
            currentCardIndex = 0;
            renderCard();
            isShuffled = true;
            shuffledIndicator.classList.remove('hidden');

            shuffleNotification.classList.remove('hidden');
            setTimeout(() => {
                shuffleNotification.classList.add('hidden');
            }, 3000);
        }
    }

    function updateFlashcardListOverview() {
        flashcardListOverview.innerHTML = '';
        flashcards.forEach((card, index) => {
            const cardItem = document.createElement('div');
            cardItem.className = `p-2 rounded-md cursor-pointer transition-colors duration-200 text-sm font-semibold flex items-center justify-center h-10 
                                    ${index === currentCardIndex ? 'bg-blue-200 text-blue-800 border border-blue-400' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'}`;
            cardItem.textContent = `${index + 1}`;
            cardItem.dataset.index = index;

            cardItem.addEventListener('click', () => {
                if (index !== currentCardIndex) {
                    transitionCard(() => currentCardIndex = index);
                }
            });
            flashcardListOverview.appendChild(cardItem);
        });
    }

    // UBAH: Event Listener untuk tombol baru "Ubah Awal Kartu"
    toggleSideButton.addEventListener('click', function() {
        displaySideBFirst = !displaySideBFirst; // Membalik nilai boolean
        localStorage.setItem('displaySideBFirst', displaySideBFirst); // Simpan preferensi
        renderCard(); // Render ulang kartu dan perbarui indikator
    });

    nextButton.addEventListener('click', nextCard);
    prevButton.addEventListener('click', prevCard);
    shuffleButton.addEventListener('click', shuffleFlashcards);

    document.addEventListener('DOMContentLoaded', () => {
        // Karena ini bukan lagi checkbox, kita tidak perlu mengatur .checked
        renderCard(); // Panggil renderCard() untuk mengatur status awal dan indikator
    });
</script>
@endpush
@endpush
@endpush