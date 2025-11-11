<?php

namespace App\Http\Controllers;

use App\Models\Report;

class ReportController extends Controller
{
    public function show($id)
    {
        $report = Report::with([
            'journeys' => function ($query) {
                $query->with('evidences')->orderByDesc('created_at');
            },
            'category',
        ])->findOrFail($id);

        return view('pages.reports.detail', compact('report'));
    }
}
