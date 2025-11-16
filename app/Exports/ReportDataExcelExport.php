<?php

namespace App\Exports;

use App\Services\ReportDataService;
use Carbon\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportDataExcelExport
{
    public function __construct(protected ReportDataService $service, protected array $filters = [])
    {
    }

    public function download(string $filename)
    {
        $reports = $this->service->buildQuery($this->filters)->get();

        $rowNumber = 0;

        return (new FastExcel($reports))->download($filename, function ($report) use (&$rowNumber) {
            $incident = $report->incident_datetime instanceof Carbon
                ? $report->incident_datetime
                : ($report->incident_datetime ? Carbon::parse($report->incident_datetime) : null);

            $created = $report->created_at instanceof Carbon
                ? $report->created_at
                : ($report->created_at ? Carbon::parse($report->created_at) : null);

            $finishRaw = $report->finish_time;
            if ($finishRaw instanceof Carbon) {
                $finish = $finishRaw;
            } elseif (is_numeric($finishRaw)) {
                $finish = Carbon::createFromTimestamp((int) $finishRaw);
            } elseif ($finishRaw) {
                $finish = Carbon::parse($finishRaw);
            } else {
                $finish = null;
            }

            return [
                'No' => ++$rowNumber,
                'Kode' => $report->code,
                'Judul' => $report->title,
                'Kategori' => optional($report->category)->name,
                'Status' => $report->status,
                'Tanggal Kejadian' => $incident ? $incident->format('d/m/Y H:i') : '-',
                'Provinsi' => optional($report->province)->name,
                'Kota/Kabupaten' => optional($report->city)->name,
                'Kecamatan' => optional($report->district)->name,
                'Dibuat' => $created ? $created->format('d/m/Y H:i') : '-',
                'Selesai' => $finish ? $finish->format('d/m/Y H:i') : '-',
            ];
        });
    }
}
