<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\DashboardRepository;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    protected $dashboardRepo;

    public function __construct(DashboardRepository $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.index');
    }

    public function statusSummary()
    {
        $statusSummary = $this->dashboardRepo->getStatusSummary();
        return response()->json($statusSummary);
    }
    
    public function topCategories()
    {
        $topCategories = $this->dashboardRepo->getTopCategories();
        return response()->json($topCategories);
    }

    public function getTrendReports()
    {
        $data = $this->dashboardRepo->getTrendReports();

        return response()->json($data);
    }

    public function getTotalReports()
    {
        $total = $this->dashboardRepo->getTotalReports();

        return response()->json([
            'total' => $total
        ]);
    }

    public function getTopCategoryAktif()
    {
        $top = $this->dashboardRepo->getTopCategoryAktif();

        return response()->json([
            'category' => $top->category ?? '-',
            'total' => $top->total ?? 0
        ]);
    }

    public function getLaporanAktif()
    {
        $aktif = $this->dashboardRepo->getLaporanAktif();

        return response()->json([
            'aktif' => $aktif
        ]);
    }

    public function getPersentasiLaporanSelesai()
    {
        $rate = $this->dashboardRepo->getPersentasiLaporanSelesai();
        return response()->json(['rate' => $rate]);
    }

    public function getAverage()
    {
        $avgFinish = $this->dashboardRepo->getAverage();

        return view('dashboard.index', [
            'avgFinish' => $avgFinish
        ]);
    }

    public function getAvgResolution()
    {
        $avg = $this->dashboardRepo->getAverageResolutionTime();

        return response()->json([
            'avg_resolution_time' => $avg
        ]);
    }

    public function recentReports()
    {
        return response()->json(
            $this->dashboardRepo->getRecentReports()
        );
    }

    public function kpiWithEvidence()
    {
        $rate = $this->dashboardRepo->getPercentWithEvidence();

        return response()->json([
            'rate' => $rate
        ]);
    }

    public function reportsWithoutEvidence(Request $request)
    {
        $query = $this->dashboardRepo->getReportsWithoutEvidenceQuery();

        // ambil semua data tanpa paginate
        $reports = $query->get();

        $data = [];
        foreach ($reports as $key => $report) {
            // ambil institution dari report_journeys
            $institusi = $report->journeys
                ->pluck('institution.type')
                ->filter()
                ->unique()
                ->implode(', ') ?: '-';

            $data[] = [
                'DT_RowIndex' => $key + 1,
                'code' => $report->code,
                'kategori' => $report->category?->name ?? '-',
                'institusi' => $institusi,
            ];
        }

        return response()->json([
            'data' => $data,
        ]);
    }


}
