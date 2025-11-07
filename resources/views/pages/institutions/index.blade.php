@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa">
                        <i class="fa-sharp fa-solid fa-filter me-2"></i>Filter
                    </div>
                    <div class="tools">
                        <a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-sm-6 mb-3 mb-xl-0">
                                <label class="form-label">Pencarian</label>
                                <input type="text" class="form-control" id="filter_q" placeholder="Cari nama atau tipe institusi">
                            </div>
                            <div class="col-xl-4 col-sm-6 mb-3 mb-xl-0">
                                <label class="form-label">Tipe Institusi</label>
                                <select id="filter_type" class="form-control default-select h-auto wide" aria-label="Filter tipe institusi">
                                    <option value="">Semua Tipe</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-4 col-sm-6 align-self-end">
                                <div>
                                    <button id="btnFilter" class="btn btn-primary me-2" type="button"><i class="fa fa-filter me-1"></i>Filter</button>
                                    <button id="btnClear" class="btn btn-danger light" type="button">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 pb-3">
                <a href="{{ route('institutions.create') }}" class="btn btn-primary btn-sm">Tambah Institusi</a>
            </div>

            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa">
                        <i class="fa-solid fa-file-lines me-1"></i>Daftar Institusi
                    </div>
                    <div class="tools">
                        <a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="institutions-table" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Institusi</th>
                                        <th>Tipe Institusi</th>
                                        <th>Dibuat Pada</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Institusi</th>
                                        <th>Tipe Institusi</th>
                                        <th>Dibuat Pada</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    jQuery(function($) {
        var table = $('#institutions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('institutions.datatables') }}',
                type: 'GET',
                data: function (d) {
                    d.filter_q = $('#filter_q').val();
                    d.filter_type = $('#filter_type').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'institutions.name' },
                { data: 'type', name: 'institutions.type' },
                { data: 'created_at', name: 'institutions.created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            columnDefs: [
                { targets: 0, className: 'text-center', width: '5%' },
                { targets: 4, className: 'text-nowrap text-center', width: '15%' }
            ],
            order: [[3, 'desc']],
            language: {
                paginate: {
                    previous: '<<',
                    next: '>>'
                }
            }
        });

        $('#btnFilter').on('click', function() {
            table.draw();
        });

        $('#btnClear').on('click', function() {
            $('#filter_q').val('');
            $('#filter_type').val('');
            table.draw();
        });
    });
</script>
@endsection
