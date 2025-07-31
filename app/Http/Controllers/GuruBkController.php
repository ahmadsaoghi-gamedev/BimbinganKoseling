<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GuruBK;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruBkController extends Controller
{
    // Menampilkan daftar akun siswa
    public function index()
    {
        $guru_bk = GuruBK::all();
        return view('guru_bk.index', compact('guru_bk'));
    }

    // Menampilkan daftar curhat rahasia untuk Guru BK dan Siswa
    public function listCurhat()
    {
        $user = auth()->user();
        
        if ($user->hasRole('siswa')) {
            // Siswa hanya bisa melihat curhat miliknya sendiri
            $curhats = \App\Models\Konsultasi::with(['user', 'conversations.sender'])
                ->where('id_siswa', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Guru BK bisa melihat semua curhat
            $curhats = \App\Models\Konsultasi::with(['user', 'conversations.sender'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('guru_bk.curhat', compact('curhats'));
    }

    // Menyimpan balasan guru BK atau siswa (komunikasi dua arah)
    public function replyToConsultation(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:5',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $konsultasi = \App\Models\Konsultasi::findOrFail($id);
        $user = auth()->user();
        
        // Tentukan sender_type berdasarkan role user
        $senderType = $user->hasRole('gurubk') ? 'gurubk' : 'siswa';
        
        // Validasi akses: siswa hanya bisa reply ke curhat miliknya sendiri
        if ($user->hasRole('siswa') && $konsultasi->id_siswa != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke curhat ini.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('curhat_attachments', 'public');
        }

        // Simpan pesan ke tabel conversations
        \App\Models\CurhatConversation::create([
            'konsultasi_id' => $konsultasi->id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'is_read' => false,
        ]);

        // Update status konsultasi
        $konsultasi->update([
            'status_baca' => 'dalam percakapan',
        ]);

        $notification = [
            'message' => 'Pesan berhasil dikirim!',
            'alert-type' => 'success'
        ];

        return redirect()->route('gurubk.curhat')->with($notification);
    }

    // Mark curhat as read
    public function markCurhatAsRead($id)
    {
        $curhat = \App\Models\Konsultasi::findOrFail($id);
        $curhat->update(['status_baca' => 'sudah dibaca']);

        $notification = [
            'message' => 'Curhat telah ditandai sebagai sudah dibaca',
            'alert-type' => 'success'
        ];

        return redirect()->route('gurubk.curhat')->with($notification);
    }

    // Menampilkan form tambah akun siswa
    public function create()
    {
        return view('guru_bk.create');
    }

    // Menyimpan akun siswa baru
    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|max:255',
            'nama' => 'required|max:255',
            'email' => 'required|max:255',
            'jenis_kelamin' => 'required|max:255',
            'no_tlp' => 'required|max:255',
            'alamat' => 'required|max:255',
        ]);

        $user = new User();
        $user->name = $request['nama'];
        $user->email = $request['email'];
        $user->password = Hash::make('password');
        $user->save();

        GuruBK::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_tlp' => $request->no_tlp,
            'alamat' => $request->alamat,
            'id_user' => $user->id,
        ]);

        $user->assignRole('gurubk');

        $notification = [
            'message' => 'Data Guru_bk berhasil disimpan',
            'alert-type' => 'success'
        ];

        return redirect()->route('guru_bk.index')->with($notification);
    }

    public function edit(string $id)
    {
        $gurubk = GuruBK::findOrFail($id);
        return view('guru_bk.edit', $gurubk);
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validate = $request->validate([
            'nip' => 'required|max:255',
            'nama' => 'required|max:150',
            'jenis_kelamin' => 'required|max:20',
            'no_tlp' => 'required|max:50',
            'alamat' => 'required|max:50',
            'email' => 'required|email|max:255',
        ]);

        $gurubk = GuruBK::findOrFail($id);
        $user = User::find($gurubk->id_user);

        if (!$user) {
            return redirect()->route('guru_bk.index')->with('error', 'User terkait tidak ditemukan.');
        }

        $user->name = $validate['nama'];
        $user->email = $validate['email'];
        $user->save();

        $gurubk->update([
            'nip' => $validate['nip'],
            'nama' => $validate['nama'],
            'jenis_kelamin' => $validate['jenis_kelamin'],
            'no_tlp' => $validate['no_tlp'],
            'alamat' => $validate['alamat'],
            'email' => $validate['email'],
        ]);

        $notification = [
            'message' => 'Data guru bk berhasil diperbaharui',
            'alert-type' => 'success'
        ];

        return redirect()->route('guru_bk.index')->with($notification);
    }

    public function destroy($id)
    {
        $gurubk = GuruBK::findOrFail($id);
        $gurubk->delete();

        $notification = array(
            'message' => 'Data guru bk berhasil dihapus',
            'alert-type' => 'success',
        );

        return redirect()->route('guru_bk.index')->with('success', 'Data Guru Berhasil Ditambahkan');
    }

    // ===== FITUR BIMBINGAN LANJUTAN =====
    
    public function bimbinganLanjutan()
    {
        $user = auth()->user();
        
        if ($user->hasRole('siswa')) {
            // Siswa hanya bisa melihat bimbingan lanjutan yang terkait dengan dirinya
            $siswa = \App\Models\Siswa::where('id_user', $user->id)->first();
            if ($siswa) {
                $bimbinganLanjutan = \App\Models\Rekap::where('jenis_bimbingan', 'lanjutan')
                    ->where('id_siswa', $siswa->id)
                    ->with('siswa')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $bimbinganLanjutan = collect();
            }
        } else {
            // Guru BK bisa melihat semua bimbingan lanjutan
            $bimbinganLanjutan = \App\Models\Rekap::where('jenis_bimbingan', 'lanjutan')
                ->with('siswa')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('guru_bk.bimbingan_lanjutan.index', compact('bimbinganLanjutan'));
    }

    public function createBimbinganLanjutan()
    {
        $siswa = \App\Models\Siswa::all();
        return view('guru_bk.bimbingan_lanjutan.create', compact('siswa'));
    }

    public function storeBimbinganLanjutan(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'masalah' => 'required|string|min:10',
            'solusi' => 'required|string|min:10',
            'tindak_lanjut' => 'required|string|min:10',
            'tanggal_bimbingan' => 'required|date',
        ]);

        \App\Models\Rekap::create([
            'id_siswa' => $request->id_siswa,
            'masalah' => $request->masalah,
            'solusi' => $request->solusi,
            'tindak_lanjut' => $request->tindak_lanjut,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'jenis_bimbingan' => 'lanjutan',
            'status' => 'selesai',
        ]);

        $notification = [
            'message' => 'Bimbingan lanjutan berhasil ditambahkan!',
            'alert-type' => 'success'
        ];

        return redirect()->route('gurubk.bimbingan-lanjutan')->with($notification);
    }

    public function editBimbinganLanjutan($id)
    {
        $bimbingan = \App\Models\Rekap::findOrFail($id);
        $siswa = \App\Models\Siswa::all();
        return view('guru_bk.bimbingan_lanjutan.edit', compact('bimbingan', 'siswa'));
    }

    public function updateBimbinganLanjutan(Request $request, $id)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'masalah' => 'required|string|min:10',
            'solusi' => 'required|string|min:10',
            'tindak_lanjut' => 'required|string|min:10',
            'tanggal_bimbingan' => 'required|date',
        ]);

        $bimbingan = \App\Models\Rekap::findOrFail($id);
        $bimbingan->update([
            'id_siswa' => $request->id_siswa,
            'masalah' => $request->masalah,
            'solusi' => $request->solusi,
            'tindak_lanjut' => $request->tindak_lanjut,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
        ]);

        $notification = [
            'message' => 'Bimbingan lanjutan berhasil diperbarui!',
            'alert-type' => 'success'
        ];

        return redirect()->route('gurubk.bimbingan-lanjutan')->with($notification);
    }

    public function destroyBimbinganLanjutan($id)
    {
        $bimbingan = \App\Models\Rekap::findOrFail($id);
        $bimbingan->delete();

        $notification = [
            'message' => 'Bimbingan lanjutan berhasil dihapus!',
            'alert-type' => 'success'
        ];

        return redirect()->route('gurubk.bimbingan-lanjutan')->with($notification);
    }

    // ===== FITUR DAFTAR CEK MASALAH =====
    
    public function daftarCekMasalah()
    {
        $daftarMasalah = [
            'akademik' => [
                'Kesulitan memahami pelajaran',
                'Nilai rendah',
                'Tidak mengerjakan tugas',
                'Sering terlambat mengumpulkan tugas',
                'Kurang motivasi belajar'
            ],
            'sosial' => [
                'Sulit bergaul dengan teman',
                'Konflik dengan teman sekelas',
                'Merasa dikucilkan',
                'Kesulitan berkomunikasi',
                'Pemalu berlebihan'
            ],
            'pribadi' => [
                'Masalah keluarga',
                'Masalah keuangan',
                'Masalah kesehatan',
                'Kurang percaya diri',
                'Mudah marah/emosional'
            ],
            'karir' => [
                'Bingung memilih jurusan',
                'Tidak tahu minat dan bakat',
                'Khawatir tentang masa depan',
                'Kurang informasi dunia kerja',
                'Tidak ada motivasi untuk melanjutkan studi'
            ]
        ];

        $user = auth()->user();
        
        if ($user->hasRole('siswa')) {
            // Siswa hanya bisa melihat data dirinya sendiri
            $siswa = \App\Models\Siswa::where('id_user', $user->id)->get();
        } else {
            // Guru BK bisa melihat semua siswa
            $siswa = \App\Models\Siswa::all();
        }
        
        return view('guru_bk.daftar_cek_masalah.index', compact('daftarMasalah', 'siswa'));
    }

    public function storeCekMasalah(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'kategori_masalah' => 'required|string',
            'masalah_terpilih' => 'required|array',
            'masalah_lain' => 'nullable|string',
            'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi',
            'catatan_guru' => 'nullable|string',
        ]);

        $masalahList = implode(', ', $request->masalah_terpilih);
        if ($request->masalah_lain) {
            $masalahList .= ', ' . $request->masalah_lain;
        }

        \App\Models\Rekap::create([
            'id_siswa' => $request->id_siswa,
            'masalah' => $masalahList,
            'solusi' => 'Perlu ditindaklanjuti - ' . $request->tingkat_urgensi,
            'tindak_lanjut' => $request->catatan_guru ?? 'Akan dijadwalkan sesi konseling',
            'tanggal_bimbingan' => now(),
            'jenis_bimbingan' => 'cek_masalah',
            'status' => 'proses',
        ]);

        $notification = [
            'message' => 'Daftar cek masalah berhasil disimpan!',
            'alert-type' => 'success'
        ];

        return redirect()->route('gurubk.daftar-cek-masalah')->with($notification);
    }
}
