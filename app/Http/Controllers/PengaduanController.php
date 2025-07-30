<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        $data['pengaduans'] = Pengaduan::all();
        return view("pengaduan.index", $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            "nis" => 'required|max:10',
            "jenis_pengaduan" => 'required|max:50',
            "gambar" => 'nullable|image',
            "laporan_pengaduan" => 'required|max:50',
            "tgl_pengaduan" => 'required|max:30',
        ]);

        // dd($validate);
        if ($request->hasFile('gambar')) {
            $filename = 'gambar' . time() . '.' . $request->file('gambar')->extension();
            $path = $request->file('gambar')->storeAs('public/images', $filename);
            $validate['gambar'] = $filename;
        } else {
            $validate['gambar'] = null;
        }

        $tanggal = date('Y-m-d');

        $Pengaduan = Pengaduan::create([
            'nis' => $validate['nis'],
            'tgl_pengaduan' => $validate['tgl_pengaduan'],
            'jenis_pengaduan' => $validate['jenis_pengaduan'],
            'gambar' => $validate['gambar'],
            'laporan_pengaduan' => $validate['laporan_pengaduan'],

        ]);

        $notification = [
            'message' => 'Data berhasil disimpan',
            'alert-type' => 'success'
        ];

        return redirect()->route('dashboard')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Pengaduan = Pengaduan::findOrFail($id);

        $Pengaduan->delete();


        $notification = array(
            'message' => "Data pengaduan berhasil dihapus",
            'alert-type' => 'success'
        );

        return redirect()->route('pengaduan.index')->with($notification);
    }
}

        
