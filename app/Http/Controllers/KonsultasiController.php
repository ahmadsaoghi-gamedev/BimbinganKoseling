<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KonsultasiController extends Controller
{
    public function index()
    {
        $data['konsultasi'] = Konsultasi::all();
        return view("konsultasi.index", $data);
    }

    public function create()
    {
        return view('konsultasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'jenis_konsultasi' => 'required|max:50',
            'tgl_konsultasi' => 'required|date',
            'topik' => 'required|max:255',
        ]);

        $konsultasi = Konsultasi::create([
            'id_siswa' => $validated['id_siswa'],
            'jenis_konsultasi' => $validated['jenis_konsultasi'],
            'tgl_konsultasi' => $validated['tgl_konsultasi'],
            'topik' => $validated['topik'],
        ]);

        // WhatsApp Notification Logic for New Consultation
        try {
            // Placeholder phone number for Guru BK (can be made dynamic later)
            $guruBkPhoneNumber = '081234567890';
            
            // Create message for Guru BK (keeping it general for privacy)
            $pesan = "Notifikasi Konsultasi Baru: Seorang siswa telah mengirimkan curhat/konsultasi. Mohon periksa dashboard aplikasi untuk melihat detailnya.";
            
            // Send WhatsApp notification via Fonnte API
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_API_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target' => $guruBkPhoneNumber,
                'message' => $pesan
            ]);
            
        } catch (\Exception $e) {
            // Log error if WhatsApp notification fails
            Log::error('Gagal mengirim notifikasi WhatsApp untuk konsultasi baru: ' . $e->getMessage());
        }

        $notification = [
            'message' => 'Data konsultasi berhasil disimpan',
            'alert-type' => 'success'
        ];

        return redirect()->route('konsultasi.index')->with($notification);
    }

    public function show(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        return view('konsultasi.show', compact('konsultasi'));
    }

    public function edit(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        return view('konsultasi.edit', compact('konsultasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id',
            'jenis_konsultasi' => 'required|max:50',
            'tgl_konsultasi' => 'required|date',
            'topik' => 'required|max:255',
        ]);

        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->update($validated);

        $notification = [
            'message' => 'Data konsultasi berhasil diperbarui',
            'alert-type' => 'success'
        ];

        return redirect()->route('konsultasi.index')->with($notification);
    }

    public function destroy(string $id)
    {
        $konsultasi = Konsultasi::findOrFail($id);
        $konsultasi->delete();

        $notification = [
            'message' => 'Data konsultasi berhasil dihapus',
            'alert-type' => 'success'
        ];

        return redirect()->route('konsultasi.index')->with($notification);
    }
}
