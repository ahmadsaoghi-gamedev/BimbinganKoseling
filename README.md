# Aplikasi Bimbingan Konseling SMKN Negeri 1 Cilaku

## Deskripsi Singkat

Aplikasi ini merupakan sistem bimbingan konseling berbasis web yang dirancang untuk memfasilitasi komunikasi dan pengelolaan data antara siswa dan Guru Bimbingan Konseling (Guru BK) di SMKN Negeri 1 Cilaku. Aplikasi ini membantu siswa dalam menyampaikan pengaduan, curhat rahasia, dan mendapatkan bimbingan secara efektif dan terorganisir.

## Tujuan Aplikasi

-   Mempermudah siswa dalam menyampaikan pengaduan dan curhat rahasia kepada Guru BK.
-   Memfasilitasi Guru BK dalam mengelola data siswa, pengaduan, dan hasil bimbingan.
-   Memberikan notifikasi WhatsApp otomatis kepada Guru BK dan siswa terkait aktivitas pengaduan dan bimbingan menggunakan layanan API Fonnte.
-   Menyediakan dashboard yang mudah digunakan untuk semua pengguna sesuai peran (siswa, Guru BK, admin).

## Fitur Utama

-   **Pengaduan Siswa:** Siswa dapat membuat laporan pengaduan yang akan diteruskan ke Guru BK.
-   **Curhat Rahasia:** Siswa dapat mengirimkan curhat rahasia yang hanya dapat dilihat oleh Guru BK.
-   **Daftar Curhat:** Guru BK dapat melihat daftar curhat rahasia siswa lengkap dengan status baca.
-   **Rekap Bimbingan:** Guru BK dapat mencatat dan mengelola hasil bimbingan dengan siswa.
-   **Manajemen Data:** Admin dapat mengelola data siswa, Guru BK, dan pelanggaran.
-   **Notifikasi WhatsApp:** Integrasi dengan API Fonnte untuk mengirim notifikasi otomatis saat pengaduan baru dibuat dan saat Guru BK memberikan balasan.

## Teknologi yang Digunakan

-   Laravel Framework (PHP)
-   Tailwind CSS untuk styling
-   SQLite sebagai database (dapat disesuaikan)
-   API Fonnte untuk notifikasi WhatsApp

## Cara Penggunaan

1. **Instalasi:** Clone repository dan jalankan `composer install`.
2. **Konfigurasi:** Salin `.env.example` menjadi `.env` dan isi variabel `FONNTE_API_TOKEN` dengan token API Fonnte Anda.
3. **Migrasi dan Seed:** Jalankan migrasi dan seed database dengan `php artisan migrate --seed`.
4. **Jalankan Server:** Gunakan `php artisan serve` untuk menjalankan aplikasi.
5. **Akses Aplikasi:** Login sebagai siswa, Guru BK, atau admin sesuai akun yang tersedia.
6. **Gunakan Fitur:** Siswa dapat membuat pengaduan dan curhat rahasia, Guru BK dapat melihat dan membalas curhat, serta mengelola data.

## Catatan Penting

-   Pastikan variabel `FONNTE_API_TOKEN` sudah diatur di file `.env` agar notifikasi WhatsApp dapat berfungsi.
-   Hak akses diatur berdasarkan peran pengguna untuk menjaga keamanan data.
-   Fitur curhat rahasia menjaga privasi siswa dengan hanya dapat diakses oleh Guru BK.

## Kontak

Untuk pertanyaan atau bantuan lebih lanjut, silakan hubungi tim pengembang aplikasi.

---

Dokumentasi ini memberikan gambaran singkat dan penting agar pengguna dan pengembang memahami tujuan dan fungsi utama aplikasi bimbingan konseling ini.
