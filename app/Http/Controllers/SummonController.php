<?php

namespace App\Http\Controllers;

use App\Models\Summon;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Services\SummonTemplateService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SummonController extends Controller
{
    protected $templateService;
    protected $whatsappService;

    public function __construct()
    {
        $this->templateService = new SummonTemplateService();
        $this->whatsappService = new WhatsAppNotificationService();
    }

    /**
     * Display a listing of summons
     */
    public function index(Request $request)
    {
        $query = Summon::with(['siswa', 'createdBy']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by student
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        // Search by student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $summons = $query->latest()->paginate(15);
        $siswas = Siswa::orderBy('nama')->get();

        return view('summons.index', compact('summons', 'siswas'));
    }

    /**
     * Show the form for creating a new summon
     */
    public function create(Request $request)
    {
        $siswa = null;
        $violations = [];
        $templates = $this->templateService->getAvailableTemplates();

        if ($request->filled('siswa_id')) {
            $siswa = Siswa::findOrFail($request->siswa_id);
            $violations = Pelanggaran::where('id_siswa', $siswa->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $siswas = Siswa::orderBy('nama')->get();

        return view('summons.create', compact('siswa', 'violations', 'templates', 'siswas'));
    }

    /**
     * Store a newly created summon
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'type' => 'required|in:letter,whatsapp,email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'scheduled_at' => 'nullable|date|after:now',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $summon = Summon::create([
            'siswa_id' => $request->siswa_id,
            'created_by' => Auth::id(),
            'type' => $request->type,
            'status' => Summon::STATUS_DRAFT,
            'subject' => $request->subject,
            'content' => $request->content,
            'template_used' => $request->template_used ?? null,
            'scheduled_at' => $request->scheduled_at,
            'recipient_name' => $request->recipient_name,
            'recipient_contact' => $request->recipient_contact,
            'notes' => $request->notes,
            'metadata' => $request->metadata ?? [],
        ]);

        return redirect()->route('summons.index')
            ->with('success', 'Surat pemanggilan berhasil dibuat.');
    }

    /**
     * Display the specified summon
     */
    public function show(Summon $summon)
    {
        $summon->load(['siswa', 'createdBy']);
        return view('summons.show', compact('summon'));
    }

    /**
     * Show the form for editing the specified summon
     */
    public function edit(Summon $summon)
    {
        $summon->load(['siswa']);
        $templates = $this->templateService->getAvailableTemplates();
        $siswas = Siswa::orderBy('nama')->get();

        return view('summons.edit', compact('summon', 'templates', 'siswas'));
    }

    /**
     * Update the specified summon
     */
    public function update(Request $request, Summon $summon)
    {
        $request->validate([
            'type' => 'required|in:letter,whatsapp,email',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $summon->update([
            'type' => $request->type,
            'subject' => $request->subject,
            'content' => $request->content,
            'template_used' => $request->template_used ?? $summon->template_used,
            'scheduled_at' => $request->scheduled_at,
            'recipient_name' => $request->recipient_name,
            'recipient_contact' => $request->recipient_contact,
            'notes' => $request->notes,
        ]);

        return redirect()->route('summons.index')
            ->with('success', 'Surat pemanggilan berhasil diperbarui.');
    }

    /**
     * Remove the specified summon
     */
    public function destroy(Summon $summon)
    {
        if ($summon->isSent()) {
            return redirect()->route('summons.index')
                ->with('error', 'Tidak dapat menghapus surat yang sudah dikirim.');
        }

        $summon->delete();

        return redirect()->route('summons.index')
            ->with('success', 'Surat pemanggilan berhasil dihapus.');
    }

    /**
     * Send the summon
     */
    public function send(Summon $summon)
    {
        if (!$summon->isDraft()) {
            return redirect()->route('summons.show', $summon)
                ->with('error', 'Hanya surat draft yang dapat dikirim.');
        }

        try {
            switch ($summon->type) {
                case Summon::TYPE_WHATSAPP:
                    $this->sendWhatsAppSummon($summon);
                    break;
                case Summon::TYPE_EMAIL:
                    $this->sendEmailSummon($summon);
                    break;
                case Summon::TYPE_LETTER:
                    $this->sendLetterSummon($summon);
                    break;
            }

            $summon->markAsSent();

            return redirect()->route('summons.show', $summon)
                ->with('success', 'Surat pemanggilan berhasil dikirim.');

        } catch (\Exception $e) {
            Log::error('Error sending summon: ' . $e->getMessage());
            
            return redirect()->route('summons.show', $summon)
                ->with('error', 'Gagal mengirim surat pemanggilan: ' . $e->getMessage());
        }
    }

    /**
     * Mark summon as attended
     */
    public function markAttended(Summon $summon)
    {
        $summon->markAsAttended();

        return redirect()->route('summons.show', $summon)
            ->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Mark summon as not attended
     */
    public function markNotAttended(Summon $summon)
    {
        $summon->markAsNotAttended();

        return redirect()->route('summons.show', $summon)
            ->with('success', 'Status kehadiran berhasil diperbarui.');
    }

    /**
     * Cancel summon
     */
    public function cancel(Summon $summon)
    {
        if ($summon->isAttended()) {
            return redirect()->route('summons.show', $summon)
                ->with('error', 'Tidak dapat membatalkan surat yang sudah dihadiri.');
        }

        $summon->cancel();

        return redirect()->route('summons.show', $summon)
            ->with('success', 'Surat pemanggilan berhasil dibatalkan.');
    }

    /**
     * Generate template content
     */
    public function generateTemplate(Request $request)
    {
        try {
            $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'template_type' => 'required|string',
                'scheduled_at' => 'nullable|date|after:now',
            ]);

            $siswa = Siswa::findOrFail($request->siswa_id);
            $violations = Pelanggaran::where('id_siswa', $siswa->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $scheduledDate = $request->scheduled_at;

            switch ($request->template_type) {
                case 'violation_summon_letter':
                    $template = $this->templateService->generateViolationSummonLetter($siswa, $violations, $scheduledDate);
                    break;
                case 'violation_whatsapp_message':
                    $template = $this->templateService->generateViolationWhatsAppMessage($siswa, $violations, $scheduledDate);
                    break;
                case 'violation_email_template':
                    $template = $this->templateService->generateViolationEmailTemplate($siswa, $violations, $scheduledDate);
                    break;
                default:
                    return response()->json(['error' => 'Template tidak ditemukan'], 400);
            }

            return response()->json($template);
        } catch (\Exception $e) {
            Log::error('Error generating template: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Terjadi kesalahan saat generate template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard for summons
     */
    public function dashboard()
    {
        $stats = [
            'total_summons' => Summon::count(),
            'draft_summons' => Summon::where('status', Summon::STATUS_DRAFT)->count(),
            'sent_summons' => Summon::whereIn('status', [Summon::STATUS_SENT, Summon::STATUS_DELIVERED, Summon::STATUS_READ])->count(),
            'attended_summons' => Summon::where('status', Summon::STATUS_ATTENDED)->count(),
            'today_summons' => Summon::today()->count(),
            'this_week_summons' => Summon::thisWeek()->count(),
        ];

        $recentSummons = Summon::with(['siswa', 'createdBy'])
            ->latest()
            ->limit(10)
            ->get();

        $todaySummons = Summon::today()
            ->with(['siswa'])
            ->get();

        $pendingSummons = Summon::pending()
            ->with(['siswa'])
            ->get();

        return view('summons.dashboard', compact('stats', 'recentSummons', 'todaySummons', 'pendingSummons'));
    }

    /**
     * Send WhatsApp summon
     */
    private function sendWhatsAppSummon(Summon $summon)
    {
        if (!$summon->recipient_contact) {
            throw new \Exception('Nomor kontak penerima tidak tersedia.');
        }

        $sent = $this->whatsappService->sendMessage($summon->recipient_contact, $summon->content);

        if (!$sent) {
            throw new \Exception('Gagal mengirim pesan WhatsApp.');
        }

        Log::info("WhatsApp summon sent to {$summon->recipient_contact} for student {$summon->siswa->nama}");
    }

    /**
     * Send email summon
     */
    private function sendEmailSummon(Summon $summon)
    {
        if (!$summon->recipient_contact) {
            throw new \Exception('Email penerima tidak tersedia.');
        }

        // TODO: Implement email sending logic
        // For now, just log the attempt
        Log::info("Email summon would be sent to {$summon->recipient_contact} for student {$summon->siswa->nama}");
        
        // Simulate success for now
        return true;
    }

    /**
     * Send letter summon (print/PDF)
     */
    private function sendLetterSummon(Summon $summon)
    {
        // For letter type, we just mark it as sent
        // The actual letter can be printed or downloaded as PDF
        Log::info("Letter summon marked as sent for student {$summon->siswa->nama}");
        
        return true;
    }

    /**
     * Auto-generate summons for students with critical violations
     */
    public function autoGenerateSummons()
    {
        try {
            // Get students with critical violations (70+ points) - simplified query
            $criticalStudents = Siswa::with('pelanggaran')->get()->filter(function ($siswa) {
                $totalPoints = $siswa->pelanggaran->sum('point_pelanggaran');
                return $totalPoints >= 70;
            });

            $generatedCount = 0;

            foreach ($criticalStudents as $student) {
                // Check if student already has a recent summon
                $existingSummon = Summon::where('siswa_id', $student->id)
                    ->where('status', '!=', Summon::STATUS_CANCELLED)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->first();

                if ($existingSummon) {
                    continue; // Skip if recent summon exists
                }

                $violations = Pelanggaran::where('id_siswa', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                // Generate template with error handling
                try {
                    $template = $this->templateService->generateViolationWhatsAppMessage($student, $violations);
                } catch (\Exception $e) {
                    \Log::error("Failed to generate template for student {$student->id}: " . $e->getMessage());
                    continue;
                }

                // Create summon with error handling
                try {
                    Summon::create([
                        'siswa_id' => $student->id,
                        'created_by' => Auth::id(),
                        'type' => Summon::TYPE_WHATSAPP,
                        'status' => Summon::STATUS_DRAFT,
                        'subject' => $template['subject'] ?? 'Surat Pemanggilan Orang Tua',
                        'content' => $template['content'] ?? 'Konten surat pemanggilan',
                        'template_used' => $template['template_used'] ?? 'default',
                        'scheduled_at' => $template['scheduled_at'] ?? now(),
                        'recipient_name' => $template['recipient_name'] ?? 'Orang Tua',
                        'recipient_contact' => $template['recipient_contact'] ?? '',
                        'metadata' => $template['metadata'] ?? [],
                    ]);

                    $generatedCount++;
                } catch (\Exception $e) {
                    \Log::error("Failed to create summon for student {$student->id}: " . $e->getMessage());
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil generate {$generatedCount} surat pemanggilan otomatis.",
                'generated_count' => $generatedCount,
            ]);

        } catch (\Exception $e) {
            \Log::error("Auto-generate summons error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat generate surat pemanggilan otomatis: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
