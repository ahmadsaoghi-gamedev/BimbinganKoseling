<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CekMasalah;
use Illuminate\Support\Facades\Auth;

class SiswaCekMasalahController extends Controller
{
    public function create()
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

        return view('siswa.cek_masalah.create', compact('daftarMasalah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_masalah' => 'required|string',
            'masalah_terpilih' => 'required|array|min:1',
            'masalah_lain' => 'nullable|string',
            'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi',
            'deskripsi_tambahan' => 'nullable|string',
        ]);

        $user = Auth::user();
        $cekMasalah = new CekMasalah();
        $cekMasalah->id_siswa = $user->siswa->id;
        $cekMasalah->kategori_masalah = $request->kategori_masalah;
        $cekMasalah->masalah_terpilih = json_encode($request->masalah_terpilih);
        $cekMasalah->masalah_lain = $request->masalah_lain;
        $cekMasalah->tingkat_urgensi = $request->tingkat_urgensi;
        $cekMasalah->deskripsi_tambahan = $request->deskripsi_tambahan;
        $cekMasalah->status = 'pending';
        $cekMasalah->save();

        return redirect()->route('siswa.cek-masalah.create')->with('success', 'Formulir cek masalah berhasil dikirim.');
    }
}
