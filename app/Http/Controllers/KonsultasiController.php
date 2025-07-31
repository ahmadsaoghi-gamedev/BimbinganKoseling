<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KonsultasiController extends Controller
{
    public function create()
    {
        return view('konsultasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isi_curhat' => 'required|string|min:10',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // Max 5MB
        ]);

        $attachmentPath = null;
        
        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('konsultasi_attachments', $fileName, 'public');
        }

        Konsultasi::create([
            'id_siswa' => Auth::user()->id,
            'isi_curhat' => $request->isi_curhat,
            'tgl_curhat' => now(),
            'status_baca' => 'belum dibaca',
            'attachment' => $attachmentPath,
        ]);

        try {
            $guruBkPhoneNumber = '081234567890';
            $pesan = "Notifikasi Konsultasi Baru: Seorang siswa telah mengirimkan curhat/konsultasi. Mohon periksa dashboard aplikasi untuk melihat detailnya.";
            Http::withHeaders(['Authorization' => env('FONNTE_API_TOKEN')])
                ->post('https://api.fonnte.com/send', [
                    'target' => $guruBkPhoneNumber,
                    'message' => $pesan,
                ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi WA untuk konsultasi baru: ' . $e->getMessage());
        }

        $notification = ['message' => 'Curhat rahasia berhasil dikirim!', 'alert-type' => 'success'];
        return redirect()->route('konsultasi.create')->with($notification);
    }
}
