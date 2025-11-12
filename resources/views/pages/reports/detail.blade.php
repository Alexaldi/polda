@extends('layouts.dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Laporan Pelanggaran</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#journeyModal">
                        <i class="fa fa-plus me-1"></i> Tambah Tahapan Penanganan
                    </button>
                </div>

                <div class="card-body">
                    <h4 class="mb-3">{{ $report->title }}</h4>
                    @php($statusLabel = \App\Enums\ReportJourneyType::tryFrom($report->status)?->label() ?? $report->status)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status Laporan:</strong> {{ $statusLabel }}</p>
                            <p class="mb-1"><strong>Waktu Kejadian:</strong> {{ optional($report->incident_datetime)->format('d M Y H:i') ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kategori:</strong> {{ optional($report->category)->name ?? '-' }}</p>
                            <p class="mb-1"><strong>Lokasi:</strong> {{ $report->address_detail ?? '-' }}</p>
                        </div>
                    </div>
                    <p class="mt-3">{{ $report->description }}</p>

                    <hr>
                    <h5 class="mt-4 mb-3"><i class="fa fa-route me-2"></i>Timeline Penanganan</h5>

                    @include('components.timeline', ['items' => $journeys])

                    @if($journeys->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $journeys->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Journey Modal --}}
<div class="modal fade" id="journeyModal" tabindex="-1" aria-labelledby="journeyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="journeyModalLabel">Tambah Tahapan Penanganan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('reports.journeys.store', $report->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" for="journey-type">Jenis Tahapan</label>
                            <select name="type" id="journey-type" class="form-select" required>
                                <option value="">-- Pilih Tahapan --</option>
                                @foreach($journeyTypes as $type)
                                    <option value="{{ $type->value }}" @selected(old('type') === $type->value)>{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6" id="limpah-institution-field" hidden>
                            <label class="form-label fw-semibold" for="institution-target">Institusi Tujuan</label>
                            <select name="institution_target_id" id="institution-target" class="form-select">
                                <option value="">-- Pilih Institusi --</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->id }}" @selected((int) old('institution_target_id') === $institution->id)>
                                        {{ $institution->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6" id="limpah-division-field" hidden>
                            <label class="form-label fw-semibold" for="division-target">Divisi Tujuan</label>
                            <select name="division_target_id" id="division-target" class="form-select">
                                <option value="">-- Pilih Divisi --</option>
                                @foreach($divisions as $division)
                                    <option
                                        value="{{ $division->id }}"
                                        data-institution="{{ $division->institution_id }}"
                                        @selected((int) old('division_target_id') === $division->id)
                                    >
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="journey-description">Deskripsi Proses</label>
                            <textarea
                                name="description"
                                id="journey-description"
                                rows="3"
                                class="form-control"
                                placeholder="Tuliskan ringkasan tahapan penanganan..."
                                required
                            >{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold" for="journey-files">Upload Bukti Pendukung</label>
                            <input
                                type="file"
                                name="files[]"
                                id="journey-files"
                                class="form-control"
                                multiple
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                            >
                            <small class="text-muted">*Bisa unggah lebih dari satu file (foto, dokumen, atau bukti lainnya).</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Simpan Tahapan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('components.file-viewer')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/pdfobject@2.2.8/pdfobject.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('journey-type');
    const institutionField = document.getElementById('limpah-institution-field');
    const divisionField = document.getElementById('limpah-division-field');
    const divisionSelect = document.getElementById('division-target');
    const institutionSelect = document.getElementById('institution-target');
    const limpahValue = 'LIMPAH';

    const toggleLimpahFields = () => {
        const isLimpah = typeSelect && typeSelect.value === limpahValue;
        [institutionField, divisionField].forEach(field => {
            if (!field) return;
            field.hidden = !isLimpah;
            field.querySelectorAll('select').forEach(select => {
                select.required = isLimpah;
            });
        });
        if (!isLimpah && divisionSelect) divisionSelect.value = '';
    };

    const filterDivisions = () => {
        if (!divisionSelect || !institutionSelect) return;
        const selectedInstitution = institutionSelect.value;
        divisionSelect.querySelectorAll('option').forEach(opt => {
            if (!opt.dataset.institution) return (opt.hidden = false);
            opt.hidden = selectedInstitution && opt.dataset.institution !== selectedInstitution;
        });
    };

    if (typeSelect) typeSelect.addEventListener('change', () => {
        toggleLimpahFields();
        filterDivisions();
    });

    if (institutionSelect) institutionSelect.addEventListener('change', filterDivisions);

    toggleLimpahFields();
    filterDivisions();
});
</script>
@endsection
    