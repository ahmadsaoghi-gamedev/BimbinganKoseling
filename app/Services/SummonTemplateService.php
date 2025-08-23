<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Pelanggaran;
use Carbon\Carbon;

class SummonTemplateService
{
    /**
     * Generate violation summon letter template
     */
    public function generateViolationSummonLetter(Siswa $siswa, $violations = [], string $scheduledDate = null): array
    {
        try {
            $totalPoints = Pelanggaran::getTotalPointsForStudent($siswa->id);
            $latestViolation = $violations->first();
            
            $scheduledDate = $scheduledDate ? Carbon::parse($scheduledDate) : now()->addDays(3);
            
            $subject = "SURAT PEMANGGILAN ORANG TUA/WALI SISWA";
            
            $content = $this->getViolationLetterTemplate($siswa, $totalPoints, $latestViolation, $scheduledDate);
            
            return [
                'subject' => $subject,
                'content' => $content,
                'template_used' => 'violation_summon_letter',
                'scheduled_at' => $scheduledDate,
                'recipient_name' => $siswa->nama_ortu ?? 'Orang Tua/Wali',
                'recipient_contact' => $siswa->no_tlp_ortu ?? $siswa->no_tlp,
                'metadata' => [
                    'total_points' => $totalPoints,
                    'violation_count' => $violations->count(),
                    'latest_violation' => $latestViolation ? $latestViolation->jenis_pelanggaran : null,
                    'template_type' => 'violation_summon',
                ]
            ];
        } catch (\Exception $e) {
            \Log::error('Error generating violation summon letter: ' . $e->getMessage());
            
            // Return a fallback template
            return [
                'subject' => "SURAT PEMANGGILAN ORANG TUA/WALI SISWA",
                'content' => "Dengan hormat,\n\nKami mengharapkan kehadiran Bapak/Ibu untuk membicarakan hal penting terkait putra/putri Anda.\n\nHormat kami,\nTim Bimbingan Konseling",
                'template_used' => 'violation_summon_letter',
                'scheduled_at' => now()->addDays(3),
                'recipient_name' => $siswa->nama_ortu ?? 'Orang Tua/Wali',
                'recipient_contact' => $siswa->no_tlp_ortu ?? $siswa->no_tlp,
                'metadata' => [
                    'total_points' => 0,
                    'violation_count' => 0,
                    'latest_violation' => null,
                    'template_type' => 'violation_summon',
                ]
            ];
        }
    }

    /**
     * Generate WhatsApp summon message template
     */
    public function generateViolationWhatsAppMessage(Siswa $siswa, $violations = [], string $scheduledDate = null): array
    {
        $totalPoints = Pelanggaran::getTotalPointsForStudent($siswa->id);
        $latestViolation = $violations->first();
        
        $scheduledDate = $scheduledDate ? Carbon::parse($scheduledDate) : now()->addDays(3);
        
        $content = $this->getViolationWhatsAppTemplate($siswa, $totalPoints, $latestViolation, $scheduledDate);
        
        return [
            'subject' => 'Pemanggilan Orang Tua - Pelanggaran Siswa',
            'content' => $content,
            'template_used' => 'violation_whatsapp_message',
            'scheduled_at' => $scheduledDate,
            'recipient_name' => $siswa->nama_ortu ?? 'Orang Tua/Wali',
            'recipient_contact' => $siswa->no_tlp_ortu ?? $siswa->no_tlp,
            'metadata' => [
                'total_points' => $totalPoints,
                'violation_count' => $violations->count(),
                'latest_violation' => $latestViolation ? $latestViolation->jenis_pelanggaran : null,
                'template_type' => 'violation_whatsapp',
            ]
        ];
    }

    /**
     * Generate email summon template
     */
    public function generateViolationEmailTemplate(Siswa $siswa, $violations = [], string $scheduledDate = null): array
    {
        $totalPoints = Pelanggaran::getTotalPointsForStudent($siswa->id);
        $latestViolation = $violations->first();
        
        $scheduledDate = $scheduledDate ? Carbon::parse($scheduledDate) : now()->addDays(3);
        
        $subject = "SURAT PEMANGGILAN ORANG TUA/WALI SISWA - {$siswa->nama}";
        
        $content = $this->getViolationEmailTemplate($siswa, $totalPoints, $latestViolation, $scheduledDate);
        
        return [
            'subject' => $subject,
            'content' => $content,
            'template_used' => 'violation_email_template',
            'scheduled_at' => $scheduledDate,
            'recipient_name' => $siswa->nama_ortu ?? 'Orang Tua/Wali',
            'recipient_contact' => $siswa->email_ortu ?? $siswa->email,
            'metadata' => [
                'total_points' => $totalPoints,
                'violation_count' => $violations->count(),
                'latest_violation' => $latestViolation ? $latestViolation->jenis_pelanggaran : null,
                'template_type' => 'violation_email',
            ]
        ];
    }

    /**
     * Get violation letter template
     */
    private function getViolationLetterTemplate(Siswa $siswa, int $totalPoints, $latestViolation = null, Carbon $scheduledDate): string
    {
        $currentDate = now()->format('d F Y');
        $scheduledDateFormatted = $scheduledDate->format('d F Y');
        $scheduledTime = $scheduledDate->format('H:i');
        
        $latestViolationText = $latestViolation ? $latestViolation->jenis_pelanggaran : 'Pelanggaran disiplin';
        
        return "SURAT PEMANGGILAN ORANG TUA/WALI SISWA

Nomor: {$this->generateLetterNumber()}
Hal: Pemanggilan Orang Tua/Wali Siswa

Kepada Yth.
Bapak/Ibu Orang Tua/Wali dari:
Nama Siswa: {$siswa->nama}
NIS: {$siswa->nis}
Kelas: {$siswa->kelas}
Jurusan: {$siswa->jurusan}

Di tempat

Dengan hormat,

Sehubungan dengan pelanggaran disiplin yang dilakukan oleh putra/putri Bapak/Ibu, maka kami mengharapkan kehadiran Bapak/Ibu untuk membicarakan hal tersebut pada:

Hari/Tanggal: {$scheduledDateFormatted}
Waktu: {$scheduledTime} WIB
Tempat: Ruang Guru BK
SMK SILIWANGI

INFORMASI PELANGGARAN:
• Total Poin Pelanggaran: {$totalPoints} poin
• Pelanggaran Terbaru: {$latestViolationText}
• Status: KRITIS (≥70 poin)

Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.

Cilaku, {$currentDate}
Guru Bimbingan Konseling
SMK SILIWANGI

_________________
Nama & Tanda Tangan

Catatan:
- Mohon membawa surat ini saat menghadiri pemanggilan
- Jika tidak dapat hadir, mohon konfirmasi terlebih dahulu
- Kehadiran orang tua sangat penting untuk pembinaan siswa";
    }

    /**
     * Get violation WhatsApp template
     */
    private function getViolationWhatsAppTemplate(Siswa $siswa, int $totalPoints, $latestViolation = null, Carbon $scheduledDate): string
    {
        $scheduledDateFormatted = $scheduledDate->format('d/m/Y');
        $scheduledTime = $scheduledDate->format('H:i');
        
        $latestViolationText = $latestViolation ? $latestViolation->jenis_pelanggaran : 'Pelanggaran disiplin';
        
        return "🚨 *SURAT PEMANGGILAN ORANG TUA* 🚨

Kepada Yth. Bapak/Ibu Orang Tua/Wali dari:

👤 *Nama Siswa*: {$siswa->nama}
🏫 *Kelas*: {$siswa->kelas}
📚 *Jurusan*: {$siswa->jurusan}

📊 *INFORMASI PELANGGARAN:*
• Total Poin: *{$totalPoints} poin*
• Pelanggaran Terbaru: {$latestViolationText}
• Status: *KRITIS* (≥70 poin)

📅 *JADWAL PEMANGGILAN:*
Tanggal: {$scheduledDateFormatted}
Waktu: {$scheduledTime} WIB
Tempat: Ruang Guru BK
SMK SILIWANGI

⚠️ *PERHATIAN:*
Mohon kehadiran Bapak/Ibu untuk membicarakan pelanggaran putra/putri.

📞 *KONFIRMASI:*
Mohon konfirmasi kehadiran atau alasan tidak dapat hadir.

Terima kasih atas perhatiannya.

_Sistem Bimbingan Konseling SMK SILIWANGI_";
    }

    /**
     * Get violation email template
     */
    private function getViolationEmailTemplate(Siswa $siswa, int $totalPoints, $latestViolation = null, Carbon $scheduledDate): string
    {
        $currentDate = now()->format('d F Y');
        $scheduledDateFormatted = $scheduledDate->format('d F Y');
        $scheduledTime = $scheduledDate->format('H:i');
        
        $latestViolationText = $latestViolation ? $latestViolation->jenis_pelanggaran : 'Pelanggaran disiplin';
        
        return "SURAT PEMANGGILAN ORANG TUA/WALI SISWA

Nomor: {$this->generateLetterNumber()}
Tanggal: {$currentDate}
Hal: Pemanggilan Orang Tua/Wali Siswa

Kepada Yth.
Bapak/Ibu Orang Tua/Wali dari:
Nama Siswa: {$siswa->nama}
NIS: {$siswa->nis}
Kelas: {$siswa->kelas}
Jurusan: {$siswa->jurusan}

Dengan hormat,

Sehubungan dengan pelanggaran disiplin yang dilakukan oleh putra/putri Bapak/Ibu, maka kami mengharapkan kehadiran Bapak/Ibu untuk membicarakan hal tersebut pada:

Hari/Tanggal: {$scheduledDateFormatted}
Waktu: {$scheduledTime} WIB
Tempat: Ruang Guru BK
SMK SILIWANGI

INFORMASI PELANGGARAN:
• Total Poin Pelanggaran: {$totalPoints} poin
• Pelanggaran Terbaru: {$latestViolationText}
• Status: KRITIS (≥70 poin)

Demikian surat ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.

Hormat kami,
Guru Bimbingan Konseling
SMK SILIWANGI

---
Email ini dikirim otomatis oleh Sistem Bimbingan Konseling SMK SILIWANGI.
Mohon tidak membalas email ini. Untuk konfirmasi, silakan hubungi Guru BK.";
    }

    /**
     * Generate letter number
     */
    private function generateLetterNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        $day = now()->format('d');
        $random = strtoupper(substr(md5(uniqid()), 0, 4));
        
        return "{$random}/SMK-SLW/BK/{$month}/{$year}";
    }

    /**
     * Get all available templates
     */
    public function getAvailableTemplates(): array
    {
        return [
            'violation_summon_letter' => [
                'name' => 'Surat Pemanggilan Pelanggaran',
                'description' => 'Template surat resmi untuk pemanggilan orang tua karena pelanggaran',
                'type' => 'letter',
            ],
            'violation_whatsapp_message' => [
                'name' => 'Pesan WhatsApp Pemanggilan',
                'description' => 'Template pesan WhatsApp untuk pemanggilan orang tua',
                'type' => 'whatsapp',
            ],
            'violation_email_template' => [
                'name' => 'Email Pemanggilan',
                'description' => 'Template email untuk pemanggilan orang tua',
                'type' => 'email',
            ],
        ];
    }
}
