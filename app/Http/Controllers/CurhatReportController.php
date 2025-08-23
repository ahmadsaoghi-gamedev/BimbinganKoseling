<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CurhatReport;
use App\Models\Konsultasi;
use App\Models\Siswa;
use App\Models\GuruBK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CurhatReportController extends Controller
{
    /**
     * Display a listing of curhat reports
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = CurhatReport::with(['siswa', 'guruBk', 'konsultasi']);

        // Filter by role
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('gurubk')) {
            $guruBk = GuruBK::where('user_id', $user->id)->first();
            if ($guruBk) {
                $query->where('guru_bk_id', $guruBk->id);
            }
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status_progress', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('tingkat_urgensi', $request->urgency);
        }

        if ($request->filled('category')) {
            $query->where('kategori_masalah', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Get statistics
        $stats = $this->getStatistics($user);

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('curhat_reports.index', compact('reports', 'stats'));
    }

    /**
     * Show the form for creating a new curhat report
     */
    public function create()
    {
        $konsultasis = Konsultasi::with('user')->get();
        $siswas = Siswa::all();
        $guruBks = GuruBK::all();

        return view('curhat_reports.create', compact('konsultasis', 'siswas', 'guruBks'));
    }

    /**
     * Store a newly created curhat report
     */
    public function store(Request $request)
    {
        $request->validate([
            'konsultasi_id' => 'required|exists:konsultasi,id',
            'siswa_id' => 'required|exists:siswa,id',
            'guru_bk_id' => 'required|exists:gurubk,id',
            'kategori_masalah' => 'required|in:' . implode(',', CurhatReport::getValidCategories()),
            'tingkat_urgensi' => 'required|in:' . implode(',', CurhatReport::getValidUrgencies()),
            'analisis_masalah' => 'nullable|string',
            'tindakan_yang_dilakukan' => 'nullable|string',
            'hasil_tindakan' => 'nullable|string',
            'rekomendasi_lanjutan' => 'nullable|string',
            'tanggal_curhat' => 'required|date',
            'jumlah_sesi' => 'nullable|integer|min:1',
            'perlu_tindak_lanjut' => 'boolean',
            'catatan_khusus' => 'nullable|string',
        ]);

        $report = CurhatReport::create($request->all());

        // Update konsultasi status if needed
        if ($request->filled('status_progress')) {
            $konsultasi = Konsultasi::find($request->konsultasi_id);
            if ($konsultasi) {
                $konsultasi->update(['status_baca' => 'selesai']);
            }
        }

        return redirect()->route('curhat-reports.index')
            ->with('success', 'Laporan curhat berhasil dibuat.');
    }

    /**
     * Display the specified curhat report
     */
    public function show(CurhatReport $curhatReport)
    {
        $this->authorizeAccess($curhatReport);

        $curhatReport->load(['siswa', 'guruBk', 'konsultasi.conversations.sender']);

        return view('curhat_reports.show', compact('curhatReport'));
    }

    /**
     * Show the form for editing the specified curhat report
     */
    public function edit(CurhatReport $curhatReport)
    {
        $this->authorizeAccess($curhatReport);

        $siswas = Siswa::all();
        $guruBks = GuruBK::all();

        return view('curhat_reports.edit', compact('curhatReport', 'siswas', 'guruBks'));
    }

    /**
     * Update the specified curhat report
     */
    public function update(Request $request, CurhatReport $curhatReport)
    {
        $this->authorizeAccess($curhatReport);

        $request->validate([
            'kategori_masalah' => 'required|in:' . implode(',', CurhatReport::getValidCategories()),
            'status_progress' => 'required|in:' . implode(',', CurhatReport::getValidStatuses()),
            'tingkat_urgensi' => 'required|in:' . implode(',', CurhatReport::getValidUrgencies()),
            'analisis_masalah' => 'nullable|string',
            'tindakan_yang_dilakukan' => 'nullable|string',
            'hasil_tindakan' => 'nullable|string',
            'rekomendasi_lanjutan' => 'nullable|string',
            'jumlah_sesi' => 'nullable|integer|min:1',
            'perlu_tindak_lanjut' => 'boolean',
            'catatan_khusus' => 'nullable|string',
        ]);

        $curhatReport->update($request->all());

        // Update timestamps based on status
        if ($request->status_progress === CurhatReport::STATUS_DALAM_PROSES && !$curhatReport->tanggal_analisis) {
            $curhatReport->markAsAnalyzed($request->analisis_masalah);
        }

        if ($request->status_progress === CurhatReport::STATUS_MENUNGGU_TINDAK_LANJUT && !$curhatReport->tanggal_tindakan) {
            $curhatReport->markAsActionTaken($request->tindakan_yang_dilakukan);
        }

        if ($request->status_progress === CurhatReport::STATUS_SELESAI && !$curhatReport->tanggal_selesai) {
            $curhatReport->markAsCompleted($request->hasil_tindakan, $request->rekomendasi_lanjutan);
        }

        return redirect()->route('curhat-reports.show', $curhatReport)
            ->with('success', 'Laporan curhat berhasil diperbarui.');
    }

    /**
     * Remove the specified curhat report
     */
    public function destroy(CurhatReport $curhatReport)
    {
        $this->authorizeAccess($curhatReport);

        $curhatReport->delete();

        return redirect()->route('curhat-reports.index')
            ->with('success', 'Laporan curhat berhasil dihapus.');
    }

    /**
     * Show dashboard with statistics
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = $this->getStatistics($user);

        // Get recent reports
        $recentReports = CurhatReport::with(['siswa', 'guruBk'])
            ->latest()
            ->limit(10)
            ->get();

        // Get urgent reports
        $urgentReports = CurhatReport::with(['siswa', 'guruBk'])
            ->urgent()
            ->active()
            ->latest()
            ->limit(5)
            ->get();

        // Get category distribution
        $categoryStats = CurhatReport::select('kategori_masalah', DB::raw('count(*) as total'))
            ->groupBy('kategori_masalah')
            ->get();

        // Get monthly trends - SQLite compatible
        $monthlyTrends = CurhatReport::select(
            DB::raw('strftime("%m", created_at) as month'),
            DB::raw('strftime("%Y", created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('curhat_reports.dashboard', compact(
            'stats',
            'recentReports',
            'urgentReports',
            'categoryStats',
            'monthlyTrends'
        ));
    }

    /**
     * Export reports to PDF/Excel
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = CurhatReport::with(['siswa', 'guruBk']);

        // Filter by role
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('gurubk')) {
            $guruBk = GuruBK::where('user_id', $user->id)->first();
            if ($guruBk) {
                $query->where('guru_bk_id', $guruBk->id);
            }
        }

        // Apply filters
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status_progress', $request->status);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        // Generate export based on format
        $format = $request->get('format', 'pdf');
        
        if ($format === 'excel') {
            return $this->exportToExcel($reports);
        }

        return $this->exportToPdf($reports);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics($user)
    {
        $query = CurhatReport::query();

        // Filter by role
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('gurubk')) {
            $guruBk = GuruBK::where('user_id', $user->id)->first();
            if ($guruBk) {
                $query->where('guru_bk_id', $guruBk->id);
            }
        }

        // Calculate average duration for SQLite compatibility
        $avgDuration = 0;
        $completedReports = $query->whereNotNull('tanggal_selesai')->get();
        if ($completedReports->count() > 0) {
            $totalDays = 0;
            foreach ($completedReports as $report) {
                if ($report->tanggal_curhat && $report->tanggal_selesai) {
                    $totalDays += $report->tanggal_curhat->diffInDays($report->tanggal_selesai);
                }
            }
            $avgDuration = $completedReports->count() > 0 ? round($totalDays / $completedReports->count(), 1) : 0;
        }

        return [
            'total' => $query->count(),
            'active' => $query->active()->count(),
            'urgent' => $query->urgent()->count(),
            'completed' => $query->where('status_progress', CurhatReport::STATUS_SELESAI)->count(),
            'this_month' => $query->whereMonth('created_at', date('m'))->count(),
            'avg_duration' => $avgDuration,
        ];
    }

    /**
     * Authorize access to curhat report
     */
    private function authorizeAccess(CurhatReport $curhatReport)
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('gurubk')) {
            return true;
        }

        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('id_user', $user->id)->first();
            if ($siswa && $curhatReport->siswa_id === $siswa->id) {
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
        $html = view('curhat_reports.export_pdf', compact('reports'))->render();
        
        // For now, return HTML that can be printed
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="rekap-curhat-rahasia.html"');
    }
}
