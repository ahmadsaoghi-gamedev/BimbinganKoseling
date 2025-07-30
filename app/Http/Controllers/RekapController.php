<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rekap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RekapController extends Controller
{
    public function index()
    {
        $data['rekap'] = Rekap::all();
        return view("rekap.index", $data);
    }

    // Menampilkan form tambah akun pelanggaran
    public function create()
    {
        return view('rekap.create');
    }

    // Menyimpan akun rekap baru
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|max:255',
            'jenis_bimbingan' => 'required|max:150',
            'tgl_bimbingan' => 'required|max:20',
            'keterangan' => 'required',
        ]);
        
        Rekap::create([
            'id_siswa' => $request->id_siswa,
            'jenis_bimbingan' => $request->jenis_bimbingan,
            'tgl_bimbingan' => $request->tgl_bimbingan,
            'keterangan' => $request->keterangan,
        ]);

        $notification = [
            'message' => 'Data rekap bimbingan berhasil disimpan',
            'alert-type' => 'success'
        ];

        return redirect()->route('rekap.index')->with($notification);
    }

    public function edit(string $id)
    {
        $bimbingan = Rekap::findOrFail($id);
        return view('rekap.edit', ['bimbingan' => $bimbingan]);
    }

    public function update(Request $request, string $id)
    {   
    // Validasi data
    $validated = $request->validate([
        'balasan' => 'required|string',
    ]);

    // Temukan data Rekap berdasarkan ID
    $rekap = Rekap::findOrFail($id);

    // Update data balasan
    $rekap->update([
        'balasan' => $validated['balasan'],
    ]);

    // Berikan notifikasi keberhasilan
    $notification = [
        'message' => 'Data rekap siswa berhasil diperbarui.',
        'alert-type' => 'success',
    ];

    return redirect()->route('rekap.index')->with($notification);
}

public function destroy($id)
    {
        $rekap = Rekap::findOrFail($id);
        $rekap->delete();

        $notification = array(
            'message' => 'Data rekap berhasil dihapus',
            'alert-type' => 'success',
        );

    return redirect()->route('rekap.index')->with($notification);
}
}