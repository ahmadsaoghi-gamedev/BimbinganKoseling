<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PelanggaranController extends Controller
{
    public function index()
    {
        $data['pelanggaran'] = Pelanggaran::all();
        return view("pelanggaran.index", $data);
    }

    // Menampilkan form tambah akun pelanggaran
    // Controller
    public function create()
    {
        return view('pelanggaran.create');
    }

    // Menyimpan akun pelanggaran baru
    public function store(Request $request)
    {
       
        // Validasi data
        $request->validate([
            'id_siswa' => 'required|max:255',
            'jenis_pelanggaran' => 'required|max:150',
            'point_pelanggaran' => 'required|max:20',
        ]);

        // Proses penyimpanan
        Pelanggaran::create([
            'id_siswa' => $request->id_siswa,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'point_pelanggaran' => $request->point_pelanggaran,
        ]);

        // Tampilkan notifikasi sukses
        $notification = [
            'message' => 'Data Siswa berhasil disimpan',
            'alert-type' => 'success'
        ];

        return redirect()->route('pelanggaran.index')->with($notification);
    }




    public function edit(string $id)
    {
        $pelanggarans = Pelanggaran::findOrFail($id);
        return view('pelanggaran.edit', $pelanggarans);
    }

    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validate = $request->validate([
            'id_siswa' => 'required|max:255',
            'jenis_pelanggaran' => 'required|max:150',
            'point_pelanggaran' => 'required|max:20',
        ]);

        // Find the Siswa record by ID
        $pelanggaran = Pelanggaran::findOrFail($id);


        // Update the Siswa information
        $pelanggaran->update([
            'id_siswa' => $validate['id_siswa'],
            'jenis_pelanggaran' => $validate['jenis_pelanggaran'],
            'point_pelanggaran' => $validate['point_pelanggaran'],
        ]);

        $notification = [
            'message' => 'Data pelanggaran siswa berhasil diperbaharui',
            'alert-type' => 'success'
        ];

        return redirect()->route('pelanggaran.index')->with($notification);
    }

    public function destroy($id)
    {
        $pelanggaran = Pelanggaran::findOrFail($id);
        $pelanggaran->delete();

        $notification = array(
            'message' => 'Data pelanggaran berhasil dihapus',
            'alert-type' => 'success',
        );

        return redirect()->route('pelanggaran.index')->with($notification);
    }
}
