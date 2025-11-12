@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa">
                        <i class="fa-solid fa-file-lines me-1"></i> Detail Laporan
                    </div>
                </div>
                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <!-- Judul Laporan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul Laporan</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->title ?? '-' }}
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->description ?? '-' }}
                                </div>
                            </div>

                            <!-- Tanggal Kejadian -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Kejadian</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ \Carbon\Carbon::parse($report->incident_datetime)->format('d M Y') ?? '-' }}
                                </div>
                            </div>

                            <!-- Provinsi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Provinsi</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->province->name ?? '-' }}
                                </div>
                            </div>

                            <!-- Kota -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kota</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->city->name ?? '-' }}
                                </div>
                            </div>

                            <!-- Kecamatan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kecamatan</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->district->name ?? '-' }}
                                </div>
                            </div>

                            <!-- Alamat Detail -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat Detail</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->address_detail ?? '-' }}
                                </div>
                            </div>

                            <!-- Kategori Laporan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori Laporan</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->reportCategory->name ?? '-' }}
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->status ?? '-' }}
                                </div>
                            </div>

                            <!-- Kode Laporan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Laporan</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->code ?? '-' }}
                                </div>
                            </div>

                            <!-- Finish Time -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Finish Time (hari)</label>
                                <div class="form-control" style="background: transparent; border-color: #3A3A4F;">
                                    {{ $report->finish_time ?? '-' }}
                                </div>
                            </div>

                            <!-- Suspects -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Suspects</label>
                                <ul class="list-group">
                                    @forelse($report->suspects as $suspect)
                                        <li class="list-group-item">
                                            <strong>{{ $suspect->name }}</strong> - {{ $suspect->description ?? '-' }}
                                        </li>
                                    @empty
                                        <li class="list-group-item">Tidak ada suspect</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('pelaporan.index') }}" class="btn btn-secondary">Kembali</a>
                            <a href="{{ route('pelaporan.edit', $report->id) }}" class="btn btn-warning">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection