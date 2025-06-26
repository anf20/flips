# Proyek Flips Arena (Aplikasi Flashcard Interaktif)

[Sertakan Gambar/GIF di sini untuk tampilan aplikasi Anda, contoh: `![Tampilan Utama Aplikasi Flips Arena](path/to/your/screenshot.png)`]

---

## Deskripsi Proyek

Flips Arena adalah aplikasi web interaktif yang dikembangkan dengan framework Laravel, didesain untuk membantu pengguna dalam proses belajar dan mengingat informasi melalui metode *flashcard*. Aplikasi ini memungkinkan pengguna untuk membuat *deck* *flashcard* mereka sendiri, menambahkan konten berupa teks dan bahkan gambar, serta melatih ingatan mereka dengan berbagai mode pembelajaran interaktif.

Proyek ini dibangun untuk menyediakan alat belajar yang fleksibel dan visual, menjadikannya ideal untuk berbagai mata pelajaran atau topik.

---

## Fitur Utama

* **Manajemen Deck Flashcard:**
    * Buat, sunting, dan hapus *deck* *flashcard* sesuai kebutuhan Anda.
    * Lihat detail setiap *deck* dan kartu-kartu yang ada di dalamnya.
* **Flashcard Interaktif:**
    * Setiap kartu memiliki dua sisi: Sisi A (Depan, dengan latar belakang merah muda) dan Sisi B (Belakang, dengan latar belakang hijau muda), yang dapat dibalik dengan sekali klik.
    * **Dukungan Gambar:** Unggah dan tampilkan gambar pada Sisi A atau Sisi B untuk pembelajaran yang lebih visual.
    * Navigasi mudah antar kartu dengan tombol "Kartu Sebelumnya" dan "Kartu Selanjutnya".
    * Fungsi "Acak Kartu" untuk mengacak urutan kartu dalam *deck*, membantu menghindari penghafalan urutan.
    * Tombol "Ubah Awal Kartu" yang memungkinkan pengguna memilih apakah kartu akan terbuka pertama kali di Sisi A atau Sisi B.
    * Indikator visual yang jelas memberitahu pengguna sisi mana yang akan terbuka duluan berdasarkan pengaturan.
* **Overview Kartu:** Panel navigasi di samping yang menampilkan semua nomor kartu dalam *deck*, memungkinkan pengguna untuk langsung melompat ke kartu tertentu.
* **Desain Responsif:** Antarmuka pengguna yang dirancang untuk bekerja dengan baik di berbagai ukuran layar, mulai dari perangkat seluler hingga desktop.
* **Notifikasi Pengguna:** Pesan umpan balik yang jelas untuk tindakan seperti pengacakan kartu atau penambahan kartu baru.

---

## Teknologi yang Digunakan

* **Backend:** PHP 8.2+
* **Framework:** Laravel 10 (atau versi terbaru yang kompatibel)
* **Database:** MySQL (direkomendasikan)
* **Frontend:**
    * **Templating:** Blade (Laravel's templating engine)
    * **JavaScript:** Vanilla JS (untuk interaktivitas)
    * **CSS Framework:** Tailwind CSS (untuk styling utility-first)
* **Image Handling:** Intervention Image (untuk pemrosesan dan *resize* gambar saat unggah)

---
