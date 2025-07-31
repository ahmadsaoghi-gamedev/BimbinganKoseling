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

    // Menampilkan daftar curhat rahasia untuk Guru BK
    public function listCurhat()
    {
        $curhats = \App\Models\Konsultasi::with('user')->orderBy('created_at', 'desc')->get();
        return view('guru_bk.curhat', compact('curhats'));
    }

    // Menyimpan balasan guru BK
    public function replyToConsultation(Request $request, $id)
    {
        $request->validate([
            'reply_guru' => 'required|string|min:10',
        ]);

        $konsultasi = \App\Models\Konsultasi::findOrFail($id);
        $konsultasi->update([
            'reply_guru' => $request->reply_guru,
            'reply_date' => now(),
            'status_baca' => 'sudah dibaca',
        ]);

        $notification = [
            'message' => 'Balasan berhasil dikirim!',
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
}
