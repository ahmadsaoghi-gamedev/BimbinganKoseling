<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Rekap;
use App\Models\CekMasalah;
use App\Models\Pengaduan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppNotificationService;

class CaseResolutionController extends Controller
{
    /**
     * Display dashboard for case resolution tracking
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get statistics for dashboard
        $stats = [
            'total_cases' => 0,
            'open_cases' => 0,
            'in_progress_cases' => 0,
            'resolved_cases' => 0,
            'closed_cases' => 0,
            'urgent_cases' => 0,
        ];

        if ($user->hasRole('gurubk')) {
            // Guru BK can see all cases
            $stats['total_cases'] = Konsultasi::count() + CekMasalah::count() + Pengaduan::count();
            $stats['open_cases'] = Konsultasi::where('case_status', 'open')->count() + 
                                  CekMasalah::where('status', 'pending')->count() +
                                  Pengaduan::where('case_status', 'open')->count();
            $stats['in_progress_cases'] = Konsultasi::where('case_status', 'in_progress')->count() + 
                                        CekMasalah::where('status', 'follow_up')->count() +
                                        Pengaduan::where('case_status', 'in_progress')->count();
            $stats['resolved_cases'] = Konsultasi::where('case_status', 'resolved')->count() + 
                                     CekMasalah::where('status', 'completed')->count() +
                                     Pengaduan::where('case_status', 'resolved')->count();
            $stats['closed_cases'] = Konsultasi::where('case_status', 'closed')->count() + 
                                   Pengaduan::where('case_status', 'closed')->count();
            $stats['urgent_cases'] = CekMasalah::where('tingkat_urgensi', 'tinggi')->count();
        } elseif ($user->hasRole('siswa')) {
            // Students can only see their own cases
            $stats['total_cases'] = Konsultasi::where('id_siswa', $user->id)->count() + 
                                   CekMasalah::where('id_siswa', $user->siswa->id)->count();
            $stats['open_cases'] = Konsultasi::where('id_siswa', $user->id)->where('case_status', 'open')->count() + 
                                  CekMasalah::where('id_siswa', $user->siswa->id)->where('status', 'pending')->count();
            $stats['in_progress_cases'] = Konsultasi::where('id_siswa', $user->id)->where('case_status', 'in_progress')->count() + 
                                        CekMasalah::where('id_siswa', $user->siswa->id)->where('status', 'follow_up')->count();
            $stats['resolved_cases'] = Konsultasi::where('id_siswa', $user->id)->where('case_status', 'resolved')->count() + 
                                     CekMasalah::where('id_siswa', $user->siswa->id)->where('status', 'completed')->count();
        }

        return view('case_resolution.dashboard', compact('stats'));
    }

    /**
     * Display all cases with filtering and search
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = null;
        $caseType = $request->get('type', 'all');
        $status = $request->get('status', 'all');
        $search = $request->get('search', '');
        
        if ($user->hasRole('gurubk')) {
            // Guru BK can see all cases
            $cases = $this->getAllCases($caseType, $status, $search);
        } elseif ($user->hasRole('siswa')) {
            // Students can only see their own cases
            $cases = $this->getStudentCases($user, $caseType, $status, $search);
        } else {
            abort(403, 'Unauthorized access');
        }

        return view('case_resolution.index', compact('cases', 'caseType', 'status', 'search'));
    }

    /**
     * Show specific case details
     */
    public function show($id, $type)
    {
        $user = auth()->user();
        $case = null;
        
        switch ($type) {
            case 'konsultasi':
                $case = Konsultasi::with(['user', 'conversations.sender'])->findOrFail($id);
                break;
            case 'cek_masalah':
                $case = CekMasalah::with('siswa')->findOrFail($id);
                break;
            case 'pengaduan':
                $case = Pengaduan::findOrFail($id);
                break;
            case 'rekap':
                $case = Rekap::with('siswa')->findOrFail($id);
                break;
            default:
                abort(404, 'Case type not found');
        }

        // Check access permissions
        if ($user->hasRole('siswa')) {
            if ($type === 'konsultasi' && $case->id_siswa != $user->id) {
                abort(403, 'Unauthorized access');
            }
            if ($type === 'cek_masalah' && $case->id_siswa != $user->siswa->id) {
                abort(403, 'Unauthorized access');
            }
        }

        return view('case_resolution.show', compact('case', 'type'));
    }

    /**
     * Update case status and resolution
     */
    public function update(Request $request, $id, $type)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('gurubk')) {
            abort(403, 'Only Guru BK can update case status');
        }

        $request->validate([
            'case_status' => 'required|in:open,in_progress,resolved,closed',
            'final_resolution' => 'required|string|min:10',
            'resolution_type' => 'required|in:counseling,disciplinary_action,mediation,referral,other',
            'resolution_notes' => 'nullable|string',
        ]);

        $case = null;
        
        switch ($type) {
            case 'konsultasi':
                $case = Konsultasi::findOrFail($id);
                $case->update([
                    'case_status' => $request->case_status,
                    'final_resolution' => $request->final_resolution,
                    'resolution_type' => $request->resolution_type,
                    'resolution_notes' => $request->resolution_notes,
                    'resolution_date' => now(),
                    'resolved_by' => $user->id,
                ]);
                break;
                
            case 'cek_masalah':
                $case = CekMasalah::findOrFail($id);
                $case->update([
                    'status' => $this->mapCaseStatusToCekMasalah($request->case_status),
                    'catatan_guru' => $request->final_resolution,
                    'tindak_lanjut' => $request->resolution_notes,
                    'tanggal_review' => now(),
                ]);
                break;
                
            case 'pengaduan':
                $case = Pengaduan::findOrFail($id);
                $case->update([
                    'case_status' => $request->case_status,
                    'final_resolution' => $request->final_resolution,
                    'resolution_type' => $request->resolution_type,
                    'resolution_notes' => $request->resolution_notes,
                    'resolution_date' => now(),
                    'resolved_by' => $user->id,
                ]);
                break;
                
            case 'rekap':
                $case = Rekap::findOrFail($id);
                $case->update([
                    'case_status' => $request->case_status,
                    'final_resolution' => $request->final_resolution,
                    'resolution_type' => $request->resolution_type,
                    'resolution_notes' => $request->resolution_notes,
                    'resolution_date' => now(),
                    'resolved_by' => $user->id,
                ]);
                break;
        }

        // Send WhatsApp notification if case is resolved
        if ($request->case_status === 'resolved') {
            try {
                $whatsappService = new WhatsAppNotificationService();
                if ($type === 'konsultasi') {
                    $whatsappService->notifyCaseResolved($case->id_siswa, 'konsultasi');
                } elseif ($type === 'cek_masalah') {
                    $whatsappService->notifyCaseResolved($case->id_siswa, 'cek_masalah');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send case resolution notification: ' . $e->getMessage());
            }
        }

        $notification = [
            'message' => 'Status kasus berhasil diperbarui!',
            'alert-type' => 'success'
        ];

        return redirect()->route('case-resolution.show', [$id, $type])->with($notification);
    }

    /**
     * Generate case resolution report
     */
    public function generateReport(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasRole('gurubk')) {
            abort(403, 'Only Guru BK can generate reports');
        }

        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());
        $caseType = $request->get('type', 'all');

        $reportData = $this->generateReportData($startDate, $endDate, $caseType);

        return view('case_resolution.report', compact('reportData', 'startDate', 'endDate', 'caseType'));
    }

    /**
     * Get all cases for Guru BK
     */
    private function getAllCases($caseType, $status, $search)
    {
        $cases = collect();

        if ($caseType === 'all' || $caseType === 'konsultasi') {
            $konsultasiQuery = Konsultasi::with(['user']);
            if ($status !== 'all') {
                $konsultasiQuery->where('case_status', $status);
            }
            if ($search) {
                $konsultasiQuery->where(function($q) use ($search) {
                    $q->where('isi_curhat', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            }
            $konsultasi = $konsultasiQuery->get()->map(function($item) {
                $item->case_type = 'konsultasi';
                return $item;
            });
            $cases = $cases->merge($konsultasi);
        }

        if ($caseType === 'all' || $caseType === 'cek_masalah') {
            $cekMasalahQuery = CekMasalah::with(['siswa']);
            if ($status !== 'all') {
                $cekMasalahQuery->where('status', $this->mapCaseStatusToCekMasalah($status));
            }
            if ($search) {
                $cekMasalahQuery->whereHas('siswa', function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            }
            $cekMasalah = $cekMasalahQuery->get()->map(function($item) {
                $item->case_type = 'cek_masalah';
                return $item;
            });
            $cases = $cases->merge($cekMasalah);
        }

        if ($caseType === 'all' || $caseType === 'pengaduan') {
            $pengaduanQuery = Pengaduan::query();
            if ($status !== 'all') {
                $pengaduanQuery->where('case_status', $status);
            }
            if ($search) {
                $pengaduanQuery->where('laporan_pengaduan', 'like', "%{$search}%");
            }
            $pengaduan = $pengaduanQuery->get()->map(function($item) {
                $item->case_type = 'pengaduan';
                return $item;
            });
            $cases = $cases->merge($pengaduan);
        }

        return $cases->sortByDesc('created_at');
    }

    /**
     * Get student cases
     */
    private function getStudentCases($user, $caseType, $status, $search)
    {
        $cases = collect();

        if ($caseType === 'all' || $caseType === 'konsultasi') {
            $konsultasiQuery = Konsultasi::where('id_siswa', $user->id);
            if ($status !== 'all') {
                $konsultasiQuery->where('case_status', $status);
            }
            if ($search) {
                $konsultasiQuery->where('isi_curhat', 'like', "%{$search}%");
            }
            $konsultasi = $konsultasiQuery->get()->map(function($item) {
                $item->case_type = 'konsultasi';
                return $item;
            });
            $cases = $cases->merge($konsultasi);
        }

        if ($caseType === 'all' || $caseType === 'cek_masalah') {
            $cekMasalahQuery = CekMasalah::where('id_siswa', $user->siswa->id);
            if ($status !== 'all') {
                $cekMasalahQuery->where('status', $this->mapCaseStatusToCekMasalah($status));
            }
            if ($search) {
                $cekMasalahQuery->where('deskripsi_tambahan', 'like', "%{$search}%");
            }
            $cekMasalah = $cekMasalahQuery->get()->map(function($item) {
                $item->case_type = 'cek_masalah';
                return $item;
            });
            $cases = $cases->merge($cekMasalah);
        }

        return $cases->sortByDesc('created_at');
    }

    /**
     * Map case status to CekMasalah status
     */
    private function mapCaseStatusToCekMasalah($caseStatus)
    {
        return match($caseStatus) {
            'open' => 'pending',
            'in_progress' => 'follow_up',
            'resolved' => 'completed',
            'closed' => 'completed',
            default => 'pending',
        };
    }

    /**
     * Generate report data
     */
    private function generateReportData($startDate, $endDate, $caseType)
    {
        $data = [
            'total_cases' => 0,
            'resolved_cases' => 0,
            'resolution_types' => [],
            'cases_by_month' => [],
            'cases_by_type' => [],
        ];

        // Add logic to generate comprehensive report data
        // This would include statistics, charts, and detailed breakdowns

        return $data;
    }
}
