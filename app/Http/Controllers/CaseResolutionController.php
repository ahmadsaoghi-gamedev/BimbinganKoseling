<?php

namespace App\Http\Controllers;

use App\Models\Konsultasi;
use App\Models\Pengaduan;
use App\Models\Rekap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaseResolutionController extends Controller
{
    /**
     * Show case resolution dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = $this->getStatistics($user);
        
        // Get recent cases that need resolution
        $recentKonsultasi = Konsultasi::with(['siswa', 'resolvedBy'])
            ->whereIn('case_status', ['open', 'in_progress'])
            ->latest('tgl_curhat')
            ->limit(10)
            ->get();
            
        $recentPengaduan = Pengaduan::with(['siswa', 'resolvedBy'])
            ->whereIn('case_status', ['open', 'in_progress'])
            ->latest('tgl_pengaduan')
            ->limit(10)
            ->get();

        // Get recent rekap cases that need resolution
        $recentRekap = Rekap::with(['siswa', 'resolvedBy'])
            ->whereIn('case_status', ['open', 'in_progress'])
            ->latest('tgl_bimbingan')
            ->limit(10)
            ->get();
            
        // Get resolved cases this month
        $resolvedThisMonth = $this->getResolvedCasesThisMonth($user);
        
        // Get case type distribution
        $caseTypeDistribution = $this->getCaseTypeDistribution($user);
        
        return view('case_resolution.dashboard', compact(
            'stats',
            'recentKonsultasi',
            'recentPengaduan',
            'recentRekap',
            'resolvedThisMonth',
            'caseTypeDistribution'
        ));
    }
    
    /**
     * Show all cases with resolution status
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all'); // konsultasi, pengaduan, rekap, all
        $status = $request->get('status', 'all');
        
        $konsultasi = collect();
        $pengaduan = collect();
        $rekap = collect();
        
        if ($type === 'all' || $type === 'konsultasi') {
            $konsultasiQuery = Konsultasi::with(['siswa', 'resolvedBy']);
            
            if ($status !== 'all') {
                $konsultasiQuery->where('case_status', $status);
            }
            
            $konsultasi = $konsultasiQuery->latest('tgl_curhat')->paginate(15);
        }
        
        if ($type === 'all' || $type === 'pengaduan') {
            $pengaduanQuery = Pengaduan::with(['siswa', 'resolvedBy']);
            
            if ($status !== 'all') {
                $pengaduanQuery->where('case_status', $status);
            }
            
            $pengaduan = $pengaduanQuery->latest('tgl_pengaduan')->paginate(15);
        }

        if ($type === 'all' || $type === 'rekap') {
            $rekapQuery = Rekap::with(['siswa', 'resolvedBy']);
            
            if ($status !== 'all') {
                $rekapQuery->where('case_status', $status);
            }
            
            $rekap = $rekapQuery->latest('tgl_bimbingan')->paginate(15);
        }
        
        return view('case_resolution.index', compact('konsultasi', 'pengaduan', 'rekap', 'type', 'status'));
    }
    
    /**
     * Show case resolution form
     */
    public function show($type, $id)
    {
        if ($type === 'konsultasi') {
            $case = Konsultasi::with(['siswa', 'resolvedBy', 'conversations'])->findOrFail($id);
        } else {
            $case = Pengaduan::with(['siswa', 'resolvedBy'])->findOrFail($id);
        }
        
        return view('case_resolution.show', compact('case', 'type'));
    }
    
    /**
     * Show resolution form
     */
    public function resolve($type, $id)
    {
        if ($type === 'konsultasi') {
            $case = Konsultasi::with(['siswa'])->findOrFail($id);
        } else {
            $case = Pengaduan::with(['siswa'])->findOrFail($id);
        }
        
        return view('case_resolution.resolve', compact('case', 'type'));
    }
    
    /**
     * Store resolution
     */
    public function storeResolution(Request $request, $type, $id)
    {
        $request->validate([
            'final_resolution' => 'required|string|min:10',
            'resolution_type' => 'required|string',
            'resolution_notes' => 'nullable|string',
            'case_status' => 'required|in:resolved,closed',
        ]);
        
        if ($type === 'konsultasi') {
            $case = Konsultasi::findOrFail($id);
        } else {
            $case = Pengaduan::findOrFail($id);
        }
        
        $case->update([
            'final_resolution' => $request->final_resolution,
            'case_status' => $request->case_status,
            'resolution_date' => now(),
            'resolved_by' => Auth::id(),
            'resolution_notes' => $request->resolution_notes,
            'resolution_type' => $request->resolution_type,
        ]);
        
        // Update status_baca for konsultasi if resolved
        if ($type === 'konsultasi' && $request->case_status === 'resolved') {
            $case->update(['status_baca' => 'selesai']);
        }
        
        return redirect()->route('case-resolution.show', [$type, $id])
            ->with('success', 'Kasus berhasil diselesaikan.');
    }
    
    /**
     * Update case status
     */
    public function updateStatus(Request $request, $type, $id)
    {
        $request->validate([
            'case_status' => 'required|in:open,in_progress,resolved,closed',
        ]);
        
        if ($type === 'konsultasi') {
            $case = Konsultasi::findOrFail($id);
        } else {
            $case = Pengaduan::findOrFail($id);
        }
        
        $case->update([
            'case_status' => $request->case_status,
        ]);
        
        return redirect()->back()->with('success', 'Status kasus berhasil diperbarui.');
    }
    
    /**
     * Get statistics
     */
    private function getStatistics($user)
    {
        $konsultasiQuery = Konsultasi::query();
        $pengaduanQuery = Pengaduan::query();
        $rekapQuery = Rekap::query();
        
        // Apply role-based filtering
        if ($user->hasRole('siswa')) {
            $konsultasiQuery->where('id_siswa', $user->id);
            $pengaduanQuery->where('nis', $user->siswa->nis ?? '');
            $rekapQuery->where('id_siswa', $user->siswa->id ?? '');
        }
        
        return [
            'total_konsultasi' => $konsultasiQuery->count(),
            'open_konsultasi' => (clone $konsultasiQuery)->where('case_status', 'open')->count(),
            'in_progress_konsultasi' => (clone $konsultasiQuery)->where('case_status', 'in_progress')->count(),
            'resolved_konsultasi' => (clone $konsultasiQuery)->where('case_status', 'resolved')->count(),
            'closed_konsultasi' => (clone $konsultasiQuery)->where('case_status', 'closed')->count(),
            
            'total_pengaduan' => $pengaduanQuery->count(),
            'open_pengaduan' => (clone $pengaduanQuery)->where('case_status', 'open')->count(),
            'in_progress_pengaduan' => (clone $pengaduanQuery)->where('case_status', 'in_progress')->count(),
            'resolved_pengaduan' => (clone $pengaduanQuery)->where('case_status', 'resolved')->count(),
            'closed_pengaduan' => (clone $pengaduanQuery)->where('case_status', 'closed')->count(),

            'total_rekap' => $rekapQuery->count(),
            'open_rekap' => (clone $rekapQuery)->where('case_status', 'open')->count(),
            'in_progress_rekap' => (clone $rekapQuery)->where('case_status', 'in_progress')->count(),
            'resolved_rekap' => (clone $rekapQuery)->where('case_status', 'resolved')->count(),
            'closed_rekap' => (clone $rekapQuery)->where('case_status', 'closed')->count(),
            
            'total_cases' => $konsultasiQuery->count() + $pengaduanQuery->count() + $rekapQuery->count(),
            'resolved_this_month' => $this->getResolvedCasesThisMonth($user)->count(),
        ];
    }
    
    /**
     * Get resolved cases this month
     */
    private function getResolvedCasesThisMonth($user)
    {
        $konsultasiQuery = Konsultasi::whereIn('case_status', ['resolved', 'closed'])
            ->whereMonth('resolution_date', now()->month)
            ->whereYear('resolution_date', now()->year);
            
        $pengaduanQuery = Pengaduan::whereIn('case_status', ['resolved', 'closed'])
            ->whereMonth('resolution_date', now()->month)
            ->whereYear('resolution_date', now()->year);

        $rekapQuery = Rekap::whereIn('case_status', ['resolved', 'closed'])
            ->whereMonth('resolution_date', now()->month)
            ->whereYear('resolution_date', now()->year);
            
        if ($user->hasRole('siswa')) {
            $konsultasiQuery->where('id_siswa', $user->id);
            $pengaduanQuery->where('nis', $user->siswa->nis ?? '');
            $rekapQuery->where('id_siswa', $user->siswa->id ?? '');
        }
        
        return $konsultasiQuery->get()->merge($pengaduanQuery->get())->merge($rekapQuery->get());
    }
    
    /**
     * Get case type distribution
     */
    private function getCaseTypeDistribution($user)
    {
        $konsultasiQuery = Konsultasi::query();
        $pengaduanQuery = Pengaduan::query();
        $rekapQuery = Rekap::query();
        
        if ($user->hasRole('siswa')) {
            $konsultasiQuery->where('id_siswa', $user->id);
            $pengaduanQuery->where('nis', $user->siswa->nis ?? '');
            $rekapQuery->where('id_siswa', $user->siswa->id ?? '');
        }
        
        return [
            'konsultasi' => $konsultasiQuery->count(),
            'pengaduan' => $pengaduanQuery->count(),
            'rekap' => $rekapQuery->count(),
        ];
    }
}
