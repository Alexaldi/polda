<?php

namespace App\Http\Controllers;

use App\Services\ReportJourneyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportJourneyController extends Controller
{
    public function __construct(
        protected ReportJourneyService $service
    ) {
    }

    public function store(Request $request, $reportId): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:PEMERIKSAAN,LIMPAH,SIDANG,SELESAI',
            'description' => 'required|string',
            'files.*' => 'nullable|file|max:4096',
        ]);

        $payload = [
            'report_id' => $reportId,
            'institution_id' => optional(auth()->user())->institution_id,
            'division_id' => optional(auth()->user())->division_id,
            'type' => $validated['type'],
            'description' => $validated['description'],
        ];

        $files = $request->file('files') ?? [];

        if (! is_array($files)) {
            $files = [$files];
        }

        try {
            $this->service->store($payload, array_filter($files));
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error', 'Gagal menambahkan tahapan penanganan.')->withInput();
        }

        return back()->with('success', 'Tahapan penanganan berhasil ditambahkan.');
    }
}
