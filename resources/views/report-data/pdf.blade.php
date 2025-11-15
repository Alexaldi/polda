<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Data Export</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .filters-table th { width: 25%; }
        .filters-table td { width: 75%; }
    </style>
</head>
<body>
<h2>Report Data</h2>
<p>Generated at: {{ $generatedAt->format('d/m/Y H:i') }}</p>

@if (!empty($filters))
    <h3>Applied Filters</h3>
    <table class="filters-table">
        <tbody>
        @foreach ($filters as $label => $value)
            <tr>
                <th>{{ $label }}</th>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Code</th>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Incident Datetime</th>
        <th>Province</th>
        <th>City</th>
        <th>District</th>
        <th>Created At</th>
        <th>Finished At</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($reports as $index => $report)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $report['code'] ?? '-' }}</td>
            <td>{{ $report['title'] ?? '-' }}</td>
            <td>{{ $report['category'] ?? '-' }}</td>
            <td>{{ $report['status'] ?? '-' }}</td>
            <td>{{ $report['incident_at'] ?? '-' }}</td>
            <td>{{ $report['province'] ?? '-' }}</td>
            <td>{{ $report['city'] ?? '-' }}</td>
            <td>{{ $report['district'] ?? '-' }}</td>
            <td>{{ $report['created_at'] ?? '-' }}</td>
            <td>{{ $report['finished_at'] ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="11" style="text-align: center;">Tidak ada data.</td>
        </tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
