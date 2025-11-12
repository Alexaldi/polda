<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportJourneyService;

class ReportJourneyController extends Controller
{
    protected $service;

    public function __construct(ReportJourneyService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request, $reportId)
    {
        $request->validate([
            'type' => 'required|in:PEMERIKSAAN,LIMPAH,SIDANG,SELESAI',
            'description' => 'required|string',
            'files.*' => 'nullable|file|max:4096',
        ]);

        $payload = [
            'report_id'      => $reportId,
            'institution_id' => optional(auth()->user())->institution_id,
            'division_id'    => optional(auth()->user())->division_id,
            'type'           => $request->type,
            'description'    => $request->description,
        ];

        $files = $request->file('files') ?? [];
        if (!is_array($files)) {
            $files = [$files];
        }

        $result = $this->service->store($payload, array_filter($files));

        if ($result['status']) {
            return redirect()
                ->route('reports.show', ['id' => $reportId, 'page' => 1])
                ->with('success', $result['message']);
        }

        return back()
            ->with('error', $result['message'])
            ->withInput();
    }
}
