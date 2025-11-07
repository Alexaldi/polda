@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">

            <!-- Filter -->
            <div class="filter cm-content-box box-primary mb-3">
                <div class="content-title SlideToolHeader">
                    <div class="cpa"><i class="fa-sharp fa-solid fa-filter me-2"></i>Filter</div>
                    <div class="tools">
                        <a href="javascript:void(0);" class="expand handle"><i class="fal fa-angle-down"></i></a>
                    </div>
                </div>
                <div class="cm-content-body">
                    <div class="row p-3">
                        <div class="col-xl-3 col-sm-6">
                            <input type="text" class="form-control" id="filter_q" placeholder="Search by Division Name">
                        </div>
                        <div class="col-xl-3 col-sm-6">
                            <button id="btnFilter" class="btn btn-primary">Filter</button>
                            <button id="btnClear" class="btn btn-danger">Clear</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambah Divisi -->
            <div class="mb-3">
                <a href="{{ route('divisions.create') }}" class="btn btn-primary btn-sm">Tambah Divisi</a>
            </div>

            <!-- DataTables -->
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa"><i class="fa-solid fa-file-lines me-1"></i>Divisi List</div>
                </div>
                <div class="cm-content-body">
                    <div class="table-responsive p-3">
                        <table id="divisions-table" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Divisi</th>
                                    <th>Tipe</th>
                                    <th>Parent Divisi</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
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
    var table = $('#divisions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("datatables.division") }}',
            type: 'GET',
            data: function(d) {
                d.filter_q = $('#filter_q').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'type', name: 'type' },
            { data: 'parent_division', name: 'parent_division' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[4, 'desc']],
    });

    $('#btnFilter').on('click', function() {
        table.draw();
    });

    $('#btnClear').on('click', function() {
        $('#filter_q').val('');
        table.draw();
    });
});
</script>
@endsection
