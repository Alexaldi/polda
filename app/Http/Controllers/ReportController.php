<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function show($id)
    {
        $report = Report::with('category')->findOrFail($id);

        // ambil journeys pakai pagination
        $journeys = $report->journeys()
            ->with('evidences')
            ->orderByDesc('created_at')
            ->paginate(5); // tampilkan 5 per halaman

        return view('pages.reports.detail', compact('report', 'journeys'));
    }
}
