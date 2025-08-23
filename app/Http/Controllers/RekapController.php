<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rekap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RekapController extends Controller
{
    // Tidak ada middleware khusus di controller ini
    // Middleware diatur di routes/web.php
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
        'tgl_bimbingan' => 'nullable|date',
        'case_status' => 'nullable|in:open,in_progress,resolved,closed',
        'final_resolution' => 'nullable|string|min:10',
        'resolution_type' => 'nullable|string',
        'resolution_notes' => 'nullable|string',
    ]);

    // Temukan data Rekap berdasarkan ID
    $rekap = Rekap::findOrFail($id);

    // Prepare update data
    $updateData = [
        'balasan' => $validated['balasan'],
    ];

    // Update tanggal bimbingan if provided
    if (isset($validated['tgl_bimbingan'])) {
        $updateData['tgl_bimbingan'] = $validated['tgl_bimbingan'];
    }

    // Update case status if provided
    if (isset($validated['case_status'])) {
        $updateData['case_status'] = $validated['case_status'];
        
        // If resolving the case, set resolution details
        if (in_array($validated['case_status'], ['resolved', 'closed'])) {
            $updateData['resolution_date'] = now();
            $updateData['resolved_by'] = Auth::id();
            
            if (isset($validated['final_resolution'])) {
                $updateData['final_resolution'] = $validated['final_resolution'];
            }
            
            if (isset($validated['resolution_type'])) {
                $updateData['resolution_type'] = $validated['resolution_type'];
            }
            
            if (isset($validated['resolution_notes'])) {
                $updateData['resolution_notes'] = $validated['resolution_notes'];
            }
        }
    }

    // Update data
    $rekap->update($updateData);

    // WhatsApp Notification Logic for Guru BK Reply
    try {
        // Get student data from related Siswa model
        $siswa = $rekap->siswa;
        
        // Check if student data and phone number exist
        if ($siswa && $siswa->no_tlp) {
            // Create personalized message for student
            $pesan = "Info Bimbingan: Halo {$siswa->nama}, Guru BK telah memberikan balasan untuk sesi bimbingan Anda tanggal {$rekap->tgl_bimbingan}. Silakan periksa aplikasi untuk melihat detailnya.";
            
            // Send WhatsApp notification via Fonnte API
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_API_TOKEN')
            ])->post('https://api.fonnte.com/send', [
                'target' => $siswa->no_tlp,
                'message' => $pesan
            ]);
        }
        
    } catch (\Exception $e) {
        // Log error if WhatsApp notification fails
        Log::error('Gagal mengirim notifikasi WhatsApp untuk balasan bimbingan: ' . $e->getMessage());
    }

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