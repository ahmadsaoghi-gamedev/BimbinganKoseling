<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ViolationReport;
use App\Models\Siswa;
use App\Models\GuruBK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViolationReportController extends Controller
{
    /**
     * Display a listing of violation reports
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ViolationReport::with(['siswa', 'guruBk']);

        // Filter by role
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('gurubk')) {
            $guruBk = GuruBK::where('user_id', $user->id)->first();
            if ($guruBk) {
                $query->where('gurubk_id', $guruBk->id);
            }
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_points')) {
            $query->where('points_after', '>=', $request->min_points);
        }

        if ($request->filled('max_points')) {
            $query->where('points_after', '<=', $request->max_points);
        }

        // Get statistics
        $stats = $this->getStatistics($user);

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('violation_reports.index', compact('reports', 'stats'));
    }

    /**
     * Show the form for creating a new violation report
     */
    public function create()
    {
        $siswas = Siswa::all();
        $guruBks = GuruBK::all();

        return view('violation_reports.create', compact('siswas', 'guruBks'));
    }

    /**
     * Store a newly created violation report
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'gurubk_id' => 'required|exists:gurubk,id',
            'category' => 'required|in:' . implode(',', array_keys(ViolationReport::CATEGORIES)),
            'violation_description' => 'required|string',
            'points_before' => 'nullable|integer|min:0|max:100',
            'points_after' => 'required|integer|min:0|max:100',
            'status' => 'required|in:' . implode(',', array_keys(ViolationReport::STATUSES)),
            'sanctions' => 'nullable|string',
            'prevention_actions' => 'nullable|string',
            'special_notes' => 'nullable|string',
            'violation_date' => 'required|date',
            'parent_notification_sent' => 'boolean',
            'summon_letter_sent' => 'boolean',
        ]);

        // Calculate total points
        $totalPoinSebelumnya = ViolationReport::calculateTotalPointsForStudent($request->siswa_id);
        $totalPoinSetelahnya = $totalPoinSebelumnya + $request->points_after;

        $report = ViolationReport::create(array_merge($request->all(), [
            'points_before' => $totalPoinSebelumnya,
            'points_after' => $totalPoinSetelahnya,
        ]));

        // Check if parent notification is needed
        if ($report->needsParentNotification()) {
            $this->sendParentNotification($report);
        }

        // Check if summon letter is needed
        if ($report->needsSummonLetter()) {
            $this->generateSummonLetter($report);
        }

        return redirect()->route('violation-reports.index')
            ->with('success', 'Laporan pelanggaran berhasil dibuat.');
    }

    /**
     * Display the specified violation report
     */
    public function show(ViolationReport $violationReport)
    {
        $this->authorizeAccess($violationReport);

        $violationReport->load(['siswa', 'guruBk']);

        // Get student violation statistics
        $studentStats = ViolationReport::getStudentViolationStats($violationReport->siswa_id);

        return view('violation_reports.show', compact('violationReport', 'studentStats'));
    }

    /**
     * Show the form for editing the specified violation report
     */
    public function edit(ViolationReport $violationReport)
    {
        $this->authorizeAccess($violationReport);

        $siswas = Siswa::all();
        $guruBks = GuruBK::all();

        return view('violation_reports.edit', compact('violationReport', 'siswas', 'guruBks'));
    }

    /**
     * Update the specified violation report
     */
    public function update(Request $request, ViolationReport $violationReport)
    {
        $this->authorizeAccess($violationReport);

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'gurubk_id' => 'required|exists:gurubk,id',
            'category' => 'required|in:' . implode(',', array_keys(ViolationReport::CATEGORIES)),
            'violation_description' => 'required|string',
            'points_before' => 'nullable|integer|min:0|max:100',
            'points_after' => 'required|integer|min:0|max:100',
            'status' => 'required|in:' . implode(',', array_keys(ViolationReport::STATUSES)),
            'sanctions' => 'nullable|string',
            'prevention_actions' => 'nullable|string',
            'special_notes' => 'nullable|string',
            'violation_date' => 'required|date',
            'parent_notification_sent' => 'boolean',
            'summon_letter_sent' => 'boolean',
        ]);

        $violationReport->update($request->all());

        // Check if parent notification is needed
        if ($violationReport->needsParentNotification() && !$violationReport->parent_notification_sent) {
            $this->sendParentNotification($violationReport);
        }

        // Check if summon letter is needed
        if ($violationReport->needsSummonLetter() && !$violationReport->summon_letter_sent) {
            $this->generateSummonLetter($violationReport);
        }

        return redirect()->route('violation-reports.show', $violationReport)
            ->with('success', 'Laporan pelanggaran berhasil diperbarui.');
    }

    /**
     * Remove the specified violation report
     */
    public function destroy(ViolationReport $violationReport)
    {
        $this->authorizeAccess($violationReport);

        $violationReport->delete();

        return redirect()->route('violation-reports.index')
            ->with('success', 'Laporan pelanggaran berhasil dihapus.');
    }

    /**
     * Show dashboard with statistics
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = $this->getStatistics($user);

        // Get recent violations
        $recentViolations = ViolationReport::with(['siswa', 'guruBk'])
            ->latest()
            ->limit(10)
            ->get();

        // Get violations that need attention
        $needsAttention = ViolationReport::with(['siswa', 'guruBk'])
            ->whereIn('status', [ViolationReport::STATUS_PENDING, ViolationReport::STATUS_PROCESSING])
            ->latest()
            ->limit(5)
            ->get();

        // Get violations that need parent notification
        $needsParentNotification = ViolationReport::with(['siswa', 'guruBk'])
            ->where('points_after', '>=', ViolationReport::THRESHOLD_ORANG_TUA)
            ->where('parent_notification_sent', false)
            ->latest()
            ->limit(5)
            ->get();

        // Get violations that need summon letter
        $needsSummonLetter = ViolationReport::with(['siswa', 'guruBk'])
            ->where('points_after', '>=', ViolationReport::THRESHOLD_SURAT_PEMANGGILAN)
            ->where('summon_letter_sent', false)
            ->latest()
            ->limit(5)
            ->get();

        // Get category distribution
        $categoryStats = ViolationReport::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // Get monthly trends - SQLite compatible
        $monthlyTrends = ViolationReport::select(
            DB::raw('strftime("%m", created_at) as month'),
            DB::raw('strftime("%Y", created_at) as year'),
            DB::raw('count(*) as total'),
            DB::raw('sum(points_after) as total_points')
        )
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Get top violating students
        $topViolatingStudents = ViolationReport::select('siswa_id', DB::raw('count(*) as violation_count'), DB::raw('sum(points_after) as total_points'))
            ->with('siswa')
            ->groupBy('siswa_id')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();

        return view('violation_reports.dashboard', compact(
            'stats',
            'recentViolations',
            'needsAttention',
            'needsParentNotification',
            'needsSummonLetter',
            'categoryStats',
            'monthlyTrends',
            'topViolatingStudents'
        ));
    }

    /**
     * Export reports to PDF/Excel
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = ViolationReport::with(['siswa', 'guruBk']);

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $reports = $query->get();

        // Generate export based on format
        $format = $request->get('format', 'pdf');
        
        if ($format === 'excel') {
            return $this->exportToExcel($reports);
        }

        return $this->exportToPdf($reports);
    }

    /**
     * Send parent notification
     */
    public function sendParentNotification(ViolationReport $violationReport)
    {
        try {
            $siswa = $violationReport->siswa;
            $whatsappNumber = $siswa->getWhatsAppNumber();
            
            if (!$whatsappNumber) {
                return false;
            }

            $message = $this->prepareParentNotificationMessage($violationReport);
            
            $whatsappService = new \App\Services\WhatsAppNotificationService();
            $sent = $whatsappService->sendMessage($whatsappNumber, $message);
            
            if ($sent) {
                $violationReport->update(['parent_notification_sent' => true]);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            \Log::error('Error sending parent notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate summon letter
     */
    public function generateSummonLetter(ViolationReport $violationReport)
    {
        try {
            // Generate summon letter number
            $nomorSurat = 'SP/' . date('Y') . '/' . str_pad($violationReport->id, 4, '0', STR_PAD_LEFT);
            
            $violationReport->update(['summon_letter_sent' => true]);
            
            return $nomorSurat;
        } catch (\Exception $e) {
            \Log::error('Error generating summon letter: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Prepare parent notification message
     */
    private function prepareParentNotificationMessage(ViolationReport $violationReport)
    {
        $siswa = $violationReport->siswa;
        
        $message = "🚨 *PERINGATAN PELANGGARAN SISWA* 🚨\n\n";
        $message .= "Kepada Yth. Orang Tua/Wali\n";
        $message .= "Siswa: {$siswa->nama}\n";
        $message .= "NIS: {$siswa->nis}\n\n";
        $message .= "Dengan hormat, kami memberitahukan bahwa anak Anda telah melakukan pelanggaran:\n\n";
        $message .= "📋 *Detail Pelanggaran:*\n";
        $message .= "• Kategori: {$violationReport->category_text}\n";
        $message .= "• Poin: {$violationReport->points_after}\n";
        $message .= "• Total Poin: {$violationReport->points_after}\n";
        $message .= "• Tanggal: " . $violationReport->violation_date->format('d/m/Y') . "\n\n";
        
        if ($violationReport->points_after >= ViolationReport::THRESHOLD_SURAT_PEMANGGILAN) {
            $message .= "⚠️ *PERHATIAN:* Total poin pelanggaran telah mencapai {$violationReport->points_after} poin.\n";
            $message .= "Surat pemanggilan orang tua akan segera dikirim.\n\n";
        }
        
        $message .= "Mohon kerjasamanya untuk memberikan bimbingan kepada anak Anda.\n\n";
        $message .= "_Sistem Bimbingan Konseling SMK Negeri 1 Cilaku_";
        
        return $message;
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics($user)
    {
        $query = ViolationReport::query();

        // Filter by role
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('gurubk')) {
            $guruBk = GuruBK::where('user_id', $user->id)->first();
            if ($guruBk) {
                $query->where('gurubk_id', $guruBk->id);
            }
        }

        return [
            'total' => $query->count(),
            'pending' => $query->where('status', ViolationReport::STATUS_PENDING)->count(),
            'processing' => $query->where('status', ViolationReport::STATUS_PROCESSING)->count(),
            'completed' => $query->where('status', ViolationReport::STATUS_COMPLETED)->count(),
            'this_month' => $query->whereMonth('created_at', date('m'))->count(),
            'total_points' => $query->sum('points_after'),
            'avg_points' => $query->avg('points_after'),
            'needs_parent_notification' => $query->where('points_after', '>=', ViolationReport::THRESHOLD_ORANG_TUA)
                                                ->where('parent_notification_sent', false)
                                                ->count(),
            'needs_summon_letter' => $query->where('points_after', '>=', ViolationReport::THRESHOLD_SURAT_PEMANGGILAN)
                                          ->where('summon_letter_sent', false)
                                          ->count(),
        ];
    }

    /**
     * Authorize access to violation report
     */
    private function authorizeAccess(ViolationReport $violationReport)
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('gurubk') || $user->hasRole('kesiswaan')) {
            return true;
        }

        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa && $violationReport->siswa_id === $siswa->id) {
                return true;
            }
        }

        abort(403, 'Anda tidak memiliki akses ke laporan ini.');
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($reports)
    {
        // Implementation for Excel export
        // You can use Laravel Excel package here
        return response()->json(['message' => 'Excel export not implemented yet']);
    }

    /**
     * Export to PDF
     */
    private function exportToPdf($reports)
    {
        // Implementation for PDF export
        // You can use DomPDF package here
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}
