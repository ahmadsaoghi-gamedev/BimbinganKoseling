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
        $pelanggaran = Pelanggaran::create([
            'id_siswa' => $request->id_siswa,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'point_pelanggaran' => $request->point_pelanggaran,
        ]);

        // Check if student needs WhatsApp notification for critical violations
        $this->checkAndSendViolationNotification($request->id_siswa, $pelanggaran);

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

    /**
     * Check and send WhatsApp notification for critical violations (≥65 points)
     */
    private function checkAndSendViolationNotification($studentId, $latestViolation)
    {
        // Check if student needs notification
        if (!Pelanggaran::needsNotification($studentId)) {
            return;
        }

        try {
            $siswa = \App\Models\Siswa::with('user')->findOrFail($studentId);
            $totalPoints = Pelanggaran::getTotalPointsForStudent($studentId);
            
            // Get WhatsApp number (parent or student)
            $whatsappNumber = $siswa->getWhatsAppNumber();
            
            if (!$whatsappNumber) {
                \Log::warning("No WhatsApp number found for student: {$siswa->nama} (ID: {$studentId})");
                return;
            }

            // Prepare notification message
            $message = $this->prepareViolationNotificationMessage($siswa, $totalPoints, $latestViolation);
            
            // Send WhatsApp notification
            $whatsappService = new \App\Services\WhatsAppNotificationService();
            $sent = $whatsappService->sendViolationNotification($whatsappNumber, $message);
            
            if ($sent) {
                // Mark notification as sent for the latest violation
                $latestViolation->markNotificationSent($message);
                
                \Log::info("WhatsApp violation notification sent to {$siswa->nama} ({$whatsappNumber})");
            } else {
                \Log::error("Failed to send WhatsApp violation notification to {$siswa->nama}");
            }
            
        } catch (\Exception $e) {
            \Log::error('Error sending violation notification: ' . $e->getMessage());
        }
    }

    /**
     * Prepare WhatsApp notification message for violations
     */
    private function prepareViolationNotificationMessage($siswa, $totalPoints, $latestViolation)
    {
        $message = "🚨 *PERINGATAN PELANGGARAN SISWA* 🚨\n\n";
        $message .= "Kepada Yth. Orang Tua/Wali dari:\n";
        $message .= "👤 *Nama*: {$siswa->nama}\n";
        $message .= "🏫 *Kelas*: {$siswa->kelas}\n";
        $message .= "📚 *Jurusan*: {$siswa->jurusan}\n\n";
        
        $message .= "📊 *INFORMASI PELANGGARAN:*\n";
        $message .= "• Total Poin Pelanggaran: *{$totalPoints} poin*\n";
        $message .= "• Pelanggaran Terbaru: {$latestViolation->jenis_pelanggaran}\n";
        $message .= "• Poin Pelanggaran Terbaru: {$latestViolation->point_pelanggaran} poin\n\n";
        
        $message .= "⚠️ *PERHATIAN:*\n";
        $message .= "Siswa telah mencapai batas kritis pelanggaran (≥65 poin).\n";
        $message .= "Mohon segera menghubungi Guru BK untuk konsultasi lebih lanjut.\n\n";
        
        $message .= "📞 *Hubungi Guru BK:*\n";
        $message .= "SMK SILIWANGI\n";
        $message .= "Jl. Raya Garut - Tasikmalaya\n\n";
        
        $message .= "Terima kasih atas perhatian dan kerjasamanya.\n\n";
        $message .= "_Pesan otomatis dari Sistem Bimbingan Konseling_";
        
        return $message;
    }
}
