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

                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <label class="form-label">Cari Unit</label>
                                <input type="text" class="form-control mb-xl-0 mb-3"
                                       id="filter_q" placeholder="Cari nama Unit">
                            </div>
                            <div class="col-xl-3 col-sm-6 align-self-end">
                                <div>
                                    <button id="btnFilter" class="btn btn-primary me-2" type="button">
                                        <i class="fa fa-filter me-1"></i>Filter
                                    </button>
                                    <button id="btnClear" class="btn btn-danger light" type="button">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambah Unit -->
            <div class="mb-3">
                <a href="{{ route('subdivisions.create') }}" class="btn btn-primary btn-sm">Tambah Unit</a>
            </div>

            <!-- DataTables -->
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa"><i class="fa-solid fa-file-lines me-1"></i>Daftar Unit</div>
                </div>

                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="subdivisions-table" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Unit</th>
                                        <th>Divisi Induk</th>
                                        <th>Jenis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Unit</th>
                                        <th>Divisi Induk</th>
                                        <th>Jenis</th>
                                        <th>Aksi</th>
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
    var table = $('#subdivisions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("subdivisions.datatables") }}',
            type: 'GET',
            data: function(d) {
                d.filter_q = $('#filter_q').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'parent', name: 'parent' },
            { data: 'type', name: 'type' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [[1, 'asc']],
        language: {
            paginate: { previous: '<<', next: '>>' }
        }
    });

    $('#btnFilter').on('click', function() {
        table.draw();
    });

    $('#btnClear').on('click', function() {
        $('#filter_q').val('');
        table.draw();
    });

    $(document).on('click', '.btn-detail', function() {
        var btn = $(this);
        var data = {
            id: btn.data('id'),
            name: btn.data('name') || '-',
            parent: btn.data('parent') || '-',
            level: (btn.data('level') || '-').toString().toUpperCase(),
            type: btn.data('type') || '-',
            created_at: btn.data('created_at') || '-',
            permissions: {}
        };
        try {
            var permsStr = btn.attr('data-permissions') || '{}';
            data.permissions = JSON.parse(permsStr);
        } catch (e) {
            data.permissions = {};
        }

        $('#detail-name').text(data.name);
        $('#detail-parent').text(data.parent);
        $('#detail-level').text(data.level);
        $('#detail-type').text(data.type);
        $('#detail-created').text(data.created_at);
        $('#detail-perm-inspection').text(data.permissions && data.permissions.inspection ? 'Ya' : 'Tidak');
        $('#detail-perm-investigation').text(data.permissions && data.permissions.investigation ? 'Ya' : 'Tidak');

        var modalEl = document.getElementById('subdivisionDetailModal');
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    });
});
</script>
<div class="modal fade" id="subdivisionDetailModal" tabindex="-1" aria-labelledby="subdivisionDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="subdivisionDetailModalLabel">Detail Unit</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Unit</label>
            <div id="detail-name">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Unit Induk</label>
            <div id="detail-parent">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Level</label>
            <div id="detail-level">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Tipe</label>
            <div id="detail-type">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Inspection</label>
            <div id="detail-perm-inspection">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Investigation</label>
            <div id="detail-perm-investigation">-</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Dibuat</label>
            <div id="detail-created">-</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection
