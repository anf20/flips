@extends('layouts.deck-management-layout')

@section('content')

    {{-- Sticky header for navigation buttons --}}
    <div class="fixed top-0 left-0 right-0 z-50 w-full bg-white p-4 shadow-md flex justify-between items-center">
        <a href="{{ route('flashcards.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-base font-medium">
            &larr; Kembali
        </a>
        <a href="{{ route('flips.arena', $deck->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-base font-medium">
            Mulai Flips Arena &rarr;
        </a>
    </div>

    {{-- Placeholder div to prevent content from being hidden behind the fixed header --}}
    <div class="h-16"></div> {{-- Adjust this height if the sticky header's actual height changes --}}

    <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center mt-4">Detail Deck "{{ $deck->title }}"</h2>

    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 p-3 rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8 p-4 border border-gray-200 rounded-md bg-gray-50">
        <p class="text-gray-700 mb-2"><strong>Judul Deck:</strong> {{ $deck->title }}</p>
        <p class="text-gray-700 mb-2"><strong>Deskripsi:</strong> {{ $deck->description ?: 'Tidak ada deskripsi.' }}</p>
        <p class="text-gray-700 mb-2"><strong>Kategori:</strong> {{ $deck->collection->name ?? 'Tanpa Kategori' }}</p>
        <p class="text-gray-700"><strong>Jumlah Flashcard:</strong> {{ $flashcards->count() }}</p>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="{{ route('decks.edit', $deck->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit Deck</a>
            
            <form action="{{ route('decks.destroy', $deck->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus deck ini? Semua flashcard di dalamnya juga akan terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus Deck</button>
            </form>
        </div>
    </div>

    {{-- Flex container for Flashcard heading and action buttons --}}
    {{-- Menggunakan flex-col pada layar kecil dan flex-row pada layar medium ke atas untuk penataan yang lebih baik --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 border-b pb-2">
        <h4 class="text-xl font-bold text-gray-800 mb-2 md:mb-0">Flashcard di Deck Ini</h4>
        <div class="flex flex-wrap gap-2"> {{-- Menggunakan gap-2 untuk jarak antar tombol dan flex-wrap untuk membungkus pada layar kecil --}}
            <a href="{{ route('flashcards.create_for_deck', ['deck' => $deck->id, 'expectedCount' => 1]) }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 text-base font-medium">
                + Tambah Flashcard
            </a>
            {{-- Tombol Pilih untuk mengaktifkan mode multi-delete --}}
            <button type="button" id="toggle-select-mode" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 text-base font-medium">
                Pilih
            </button>
        </div>
    </div>

    @if ($flashcards->isEmpty())
        <div class="border border-gray-200 rounded-md p-4 bg-gray-50 text-gray-600 text-center">
            <p>Tidak ada flashcard di deck ini.</p>
            <p class="mt-2 text-sm">Anda dapat menambahkannya melalui form Tambah Deck (akan ada opsi untuk menambah flashcard kosong) atau membuat fitur tambah flashcard langsung di sini nantinya.</p>
        </div>
    @else
        {{-- Form untuk penghapusan massal flashcard --}}
        <form id="bulk-delete-form" action="{{ route('flashcards.bulk_destroy', $deck->id) }}" method="POST">
            @csrf
            @method('DELETE') {{-- Penting: Gunakan method DELETE untuk RESTful API --}}

            {{-- Header untuk mode pemilihan --}}
            <div id="selection-header" class="flex justify-between items-center mb-4 p-3 bg-gray-100 rounded-lg border border-gray-200 hidden">
                <label for="select-all-flashcards" class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" id="select-all-flashcards" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                    <span class="text-gray-700 font-semibold">Pilih Semua</span>
                </label>
                <button type="submit" id="delete-selected-button" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Hapus yang Dipilih (<span id="selected-count">0</span>)
                </button>
            </div>

            <div class="space-y-4">
                @foreach ($flashcards as $flashcard)
                    {{-- Ubah div flashcard menjadi link yang dapat diklik --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200 flex items-center transition-all duration-200 hover:shadow-md cursor-pointer flashcard-item-container">
                        {{-- Checkbox untuk pemilihan individual --}}
                        <input type="checkbox" name="flashcard_ids[]" value="{{ $flashcard->id }}" class="flashcard-checkbox hidden h-5 w-5 text-blue-600 rounded focus:ring-blue-500 mr-3 flex-shrink-0">
                        
                        {{-- Konten flashcard yang sekarang bisa diklik untuk edit --}}
                        {{-- Tambahkan data-href untuk JS agar bisa navigasi saat mode tidak aktif --}}
                        <a href="{{ route('flashcards.edit', $flashcard->id) }}" class="flex-grow block text-current no-underline" data-flashcard-id="{{ $flashcard->id }}">
                            <p class="font-semibold text-gray-700 break-words">Sisi A: <span class="font-normal">{{ $flashcard->side_a }}</span></p>
                            <p class="text-gray-600 break-words">Sisi B: <span class="font-normal">{{ $flashcard->side_b }}</span></p>
                        </a>
                        {{-- Tombol edit dan hapus individual telah dihapus --}}
                    </div>
                @endforeach
            </div>
        </form>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleSelectButton = document.getElementById('toggle-select-mode');
            const selectionHeader = document.getElementById('selection-header');
            const flashcardCheckboxes = document.querySelectorAll('.flashcard-checkbox');
            const selectAllCheckbox = document.getElementById('select-all-flashcards');
            const deleteSelectedButton = document.getElementById('delete-selected-button');
            const selectedCountSpan = document.getElementById('selected-count');
            const bulkDeleteForm = document.getElementById('bulk-delete-form');
            const flashcardItemContainers = document.querySelectorAll('.flashcard-item-container');

            let isSelectionMode = false;

            // Fungsi untuk memperbarui status tombol "Hapus yang Dipilih"
            function updateDeleteButtonStatus() {
                const checkedCount = document.querySelectorAll('.flashcard-checkbox:checked').length;
                selectedCountSpan.textContent = checkedCount;
                deleteSelectedButton.disabled = checkedCount === 0;
            }

            // Toggle mode pemilihan
            toggleSelectButton.addEventListener('click', function () {
                isSelectionMode = !isSelectionMode;

                flashcardCheckboxes.forEach(checkbox => {
                    checkbox.classList.toggle('hidden', !isSelectionMode);
                    if (!isSelectionMode) {
                        checkbox.checked = false; // Hapus centang saat keluar mode
                    }
                });

                // Ubah perilaku klik pada kontainer flashcard
                flashcardItemContainers.forEach(container => {
                    const link = container.querySelector('a');
                    if (isSelectionMode) {
                        // Dalam mode pemilihan, klik pada kontainer akan memicu checkbox
                        container.style.cursor = 'pointer'; // Menunjukkan bahwa bisa diklik
                        // Hapus atribut href dari link agar tidak langsung navigasi
                        if (link) {
                            link.removeAttribute('href');
                        }
                    } else {
                        // Keluar dari mode pemilihan, kembalikan perilaku link
                        container.style.cursor = 'default';
                        if (link && link.dataset.flashcardId) {
                            link.setAttribute('href', `{{ url('/flashcards') }}/${link.dataset.flashcardId}/edit`);
                        }
                    }
                });

                if (isSelectionMode) {
                    selectionHeader.classList.remove('hidden');
                    this.textContent = 'Batal Pilih'; // Ubah teks tombol
                } else {
                    selectionHeader.classList.add('hidden');
                    selectAllCheckbox.checked = false; // Hapus centang "Pilih Semua"
                    this.textContent = 'Pilih'; // Kembalikan teks tombol
                }
                updateDeleteButtonStatus();
            });

            // "Pilih Semua" checkbox
            selectAllCheckbox.addEventListener('change', function () {
                flashcardCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButtonStatus();
            });

            // Perbarui status tombol ketika checkbox individual berubah
            flashcardCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    updateDeleteButtonStatus();
                    // Jika ada satu saja yang tidak terpilih, uncheck "Pilih Semua"
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        // Jika semua terpilih, centang "Pilih Semua"
                        const allChecked = Array.from(flashcardCheckboxes).every(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            });

            // Tambahkan event listener ke kontainer flashcard untuk mengaktifkan checkbox
            flashcardItemContainers.forEach(container => {
                container.addEventListener('click', function(event) {
                    // Pastikan klik tidak berasal dari checkbox itu sendiri untuk menghindari double-toggle
                    // Juga pastikan klik tidak berasal dari elemen <a> jika mode tidak aktif (agar link berfungsi)
                    if (isSelectionMode && event.target !== this.querySelector('.flashcard-checkbox')) {
                        const checkbox = this.querySelector('.flashcard-checkbox');
                        if (checkbox) {
                            checkbox.checked = !checkbox.checked;
                            updateDeleteButtonStatus();
                            // Perbarui status "Pilih Semua" jika perlu
                            const allChecked = Array.from(flashcardCheckboxes).every(cb => cb.checked);
                            selectAllCheckbox.checked = allChecked;
                        }
                    } else if (!isSelectionMode) {
                        // Jika tidak dalam mode pemilihan, biarkan link <a> bekerja secara normal
                        const link = this.querySelector('a');
                        if (link && link.getAttribute('href')) {
                            window.location.href = link.getAttribute('href');
                        }
                    }
                });
            });


            // Konfirmasi sebelum menghapus massal
            bulkDeleteForm.addEventListener('submit', function (event) {
                const checkedCount = document.querySelectorAll('.flashcard-checkbox:checked').length;
                if (checkedCount === 0) {
                    // Mengganti alert() dengan pesan yang lebih ramah pengguna atau modal
                    // Untuk tujuan demo, tetap menggunakan alert() karena keterbatasan lingkungan
                    alert('Tidak ada flashcard yang dipilih untuk dihapus.');
                    event.preventDefault(); // Mencegah submit form
                    return;
                }
                if (!confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} flashcard yang dipilih?`)) {
                    event.preventDefault(); // Mencegah submit form
                }
            });
        });
    </script>
@endsection
