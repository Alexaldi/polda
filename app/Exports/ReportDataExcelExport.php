<?php

namespace App\Exports;

use App\Services\ReportDataService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportDataExcelExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    protected int $rowNumber = 0;

    public function __construct(protected ReportDataService $service, protected array $filters = [])
    {
    }

    public function query(): Builder
    {
        return $this->service->buildQuery($this->filters);
    }

    /**
     * @param  \App\Models\Report  $report
     */
    public function map($report): array
    {
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
            ++$this->rowNumber,
            $report->code,
            $report->title,
            optional($report->category)->name,
            $report->status,
            $incident ? $incident->format('d/m/Y H:i') : '-',
            optional($report->province)->name,
            optional($report->city)->name,
            optional($report->district)->name,
            $created ? $created->format('d/m/Y H:i') : '-',
            $finish ? $finish->format('d/m/Y H:i') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Code',
            'Title',
            'Category',
            'Status',
            'Incident Datetime',
            'Province',
            'City',
            'District',
            'Created At',
            'Finished At',
        ];
    }
}
