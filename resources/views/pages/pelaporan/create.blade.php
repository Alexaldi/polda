@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="filter cm-content-box box-primary">
                <div class="content-title SlideToolHeader">
                    <div class="cpa">
                        <i class="fa-solid fa-file-plus me-1"></i>{{ isset($pelaporan) ? 'Edit Laporan' : 'Tambah Laporan' }}
                    </div>
                </div>
                <div class="cm-content-body form excerpt">
                    <div class="card-body">
                        <form action="{{ isset($pelaporan) ? route('pelaporan.update', $pelaporan->id) : route('pelaporan.store') }}" method="POST">
                            @csrf
                            @if(isset($pelaporan))
                                @method('PUT')
                            @endif
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <h5 class="fw-semibold">Data Identitas Pelapor</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Pelapor</label>
                                    <input type="text" name="reporter_name" class="form-control" value="{{ old('reporter.name') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No Telepon Pelapor</label>
                                    <input type="text" name="reporter_phone" class="form-control" value="{{ old('reporter.phone') }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Alamat Pelapor</label>
                                    <textarea type="text" name="reporter_address" class="form-control" value="{{ old('reporter.address') }}"></textarea>
                                </div>

                                <div class="col-12"><hr></div>

                                <div class="col-12 mb-3">
                                    <h5 class="fw-semibold">Data Laporan</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $pelaporan->title ?? '') }}" required>
                                    @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" name="incident_datetime" class="form-control" value="{{ old('incident_datetime', isset($pelaporan->incident_datetime) ? \Carbon\Carbon::parse($pelaporan->incident_datetime)->format('Y-m-d') : '') }}" required>
                                    @error('incident_datetime')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori Laporan <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-control select2" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $pelaporan->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kota <span class="text-danger">*</span></label>
                                    <select name="city_id" id="city_id" class="form-control select2" required>
                                        <option value="">Pilih Kota</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $pelaporan->city_id ?? '') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control select2" required>
                                        <option value="">Pilih Kecamatan</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ old('district_id', $pelaporan->district_id ?? '') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('district_id')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alamat Detail</label>
                                    <input type="text" name="address_detail" class="form-control" value="{{ old('address_detail', $pelaporan->address_detail ?? '') }}">
                                    @error('address_detail')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Kronologi <span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" required>{{ old('description', $pelaporan->description ?? '') }}</textarea>
                                    @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12"><hr></div>

                                <div class="col-12 mb-3 d-flex align-items-center justify-content-between">
                                    <h5 class="fw-semibold mb-0">Data Identitas Terlapor</h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#terlaporModal">Tambah Terlapor</button>
                                </div>
                                <div class="col-12 mb-2">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Telepon</th>
                                                <th>Jenis Satuan</th>
                                                <th>Satker/Satwil</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="suspects-table-body"></tbody>
                                    </table>
                                    <div id="suspects-hidden-inputs" style="display:none"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success mt-3">{{ isset($pelaporan) ? 'Update' : 'Simpan' }}</button>
                            <a href="{{ route('pelaporan.index') }}" class="btn btn-warning mt-3">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>

 <div class="modal fade" id="terlaporModal" tabindex="-1" aria-labelledby="terlaporModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="terlaporModalLabel">Input Data Terlapor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nama Terlapor</label>
          <input type="text" class="form-control" id="tlp-name">
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat Terlapor</label>
          <input type="text" class="form-control" id="tlp-address">
        </div>
        <div class="mb-3">
          <label class="form-label">No Telepon Terlapor</label>
          <input type="text" class="form-control" id="tlp-phone">
        </div>
        <div class="mb-3">
          <label class="form-label">Jenis Satuan</label>
          <select class="form-select" id="tlp-unit-type">
            <option value="">Pilih Jenis</option>
            <option value="Satker">Satker</option>
            <option value="Satwil">Satwil</option>
          </select>
        </div>
        <div class="mb-3" id="tlp-satker-field" style="display:none;">
          <label class="form-label">Satker</label>
          <select class="form-select" id="tlp-satker">
            <option value="">Pilih Satker</option>
          </select>
        </div>
        <div class="mb-3" id="tlp-satwil-field" style="display:none;">
          <label class="form-label">Satwil</label>
          <select class="form-select" id="tlp-satwil">
            <option value="">Pilih Satwil</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="tlp-save">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let suspectIndex = 0;
    const suspectsTableBody = $('#suspects-table-body');
    const hiddenInputs = $('#suspects-hidden-inputs');
    let suspectsData = [];

    function addSuspectRow(data) {
        suspectsData.push(data);
        const satUnit = data.unit_type === 'Satker' ? (data.satker_name || '-') : (data.satwil_name || '-');
        const rowId = `suspect-row-${suspectIndex}`;
        const row = `<tr id="${rowId}">
            <td>${data.name}</td>
            <td>${data.address || '-'}</td>
            <td>${data.phone || '-'}</td>
            <td>${data.unit_type || '-'}</td>
            <td>${satUnit}</td>
            <td><button type="button" class="btn btn-danger btn-sm" data-row="${rowId}">Hapus</button></td>
        </tr>`;
        suspectsTableBody.append(row);
        const inputs = `
            <div id="hidden-${rowId}">
                <input type="hidden" name="suspects[${suspectIndex}][name]" value="${data.name}">
                <input type="hidden" name="suspects[${suspectIndex}][address]" value="${data.address || ''}">
                <input type="hidden" name="suspects[${suspectIndex}][phone]" value="${data.phone || ''}">
                <input type="hidden" name="suspects[${suspectIndex}][unit_type]" value="${data.unit_type || ''}">
                <input type="hidden" name="suspects[${suspectIndex}][satker_id]" value="${data.satker_id || ''}">
                <input type="hidden" name="suspects[${suspectIndex}][satwil_id]" value="${data.satwil_id || ''}">
            </div>`;
        hiddenInputs.append(inputs);
        suspectIndex++;
    }

    window.appendSuspectRow = addSuspectRow;

    $(document).on('click', '#suspects-table-body .btn-danger', function() {
        const rowId = $(this).data('row');
        $('#' + rowId).remove();
        $('#hidden-' + rowId).remove();
    });

    const initialSuspects = @json(old('suspects', []));
    if (Array.isArray(initialSuspects)) {
        initialSuspects.forEach(function(s) {
            window.appendSuspectRow({
                name: s.name || '',
                address: s.address || '',
                phone: s.phone || '',
                unit_type: s.unit_type || '',
                satker_id: s.satker_id || '',
                satwil_id: s.satwil_id || '',
                satker_name: s.satker_name || '',
                satwil_name: s.satwil_name || ''
            });
        });
    }
});

$(document).ready(function() {
    var oldProvinceId = '{{ old('province_id', $pelaporan->province_id ?? '') }}';
    var oldCityId = '{{ old('city_id', $pelaporan->city_id ?? '') }}';
    var oldDistrictId = '{{ old('district_id', $pelaporan->district_id ?? '') }}';

    function loadCities(provinceId, selectedCityId = null){
        if(!provinceId){
            $('#city_id').html('<option value="">Pilih Provinsi terlebih dahulu</option>');
            $('#district_id').html('<option value="">Pilih Kota terlebih dahulu</option>');
            return;
        }
        $.get('/get-cities/' + provinceId, function(data){
            $('#city_id').html('<option value="">Pilih Kota</option>');
            $.each(data, function(i, city){
                var selected = city.id == selectedCityId ? 'selected' : '';
                $('#city_id').append('<option value="'+city.id+'" '+selected+'>'+city.name+'</option>');
            });
            if(selectedCityId){
                loadDistricts(selectedCityId, oldDistrictId);
            }
        });
    }

    function loadDistricts(cityId, selectedDistrictId = null){
        if(!cityId){
            $('#district_id').html('<option value="">Pilih Kota terlebih dahulu</option>');
            return;
        }
        $.get('/get-districts/' + cityId, function(data){
            $('#district_id').html('<option value="">Pilih Kecamatan</option>');
            $.each(data, function(i, district){
                var selected = district.id == selectedDistrictId ? 'selected' : '';
                $('#district_id').append('<option value="'+district.id+'" '+selected+'>'+district.name+'</option>');
            });
        });
    }

    if(oldProvinceId){
        loadCities(oldProvinceId, oldCityId);
    }

    $('#province_id').change(function(){
        loadCities($(this).val());
    });

    $('#city_id').change(function(){
        loadDistricts($(this).val());
    });
});

document.addEventListener('DOMContentLoaded', function() {
  const unitType = document.getElementById('tlp-unit-type');
  const satkerField = document.getElementById('tlp-satker-field');
  const satwilField = document.getElementById('tlp-satwil-field');

  function toggleUnitFields() {
    const val = unitType.value;
    satkerField.style.display = val === 'Satker' ? 'block' : 'none';
    satwilField.style.display = val === 'Satwil' ? 'block' : 'none';
  }

  unitType.addEventListener('change', toggleUnitFields);

  document.getElementById('tlp-save').addEventListener('click', function() {
    const data = {
      name: document.getElementById('tlp-name').value.trim(),
      address: document.getElementById('tlp-address').value.trim(),
      phone: document.getElementById('tlp-phone').value.trim(),
      unit_type: unitType.value,
      satker_id: document.getElementById('tlp-satker').value,
      satwil_id: document.getElementById('tlp-satwil').value,
      satker_name: document.getElementById('tlp-satker').selectedOptions[0]?.text || '',
      satwil_name: document.getElementById('tlp-satwil').selectedOptions[0]?.text || ''
    };
    if (!data.name) return;
    if (window.appendSuspectRow) {
      window.appendSuspectRow(data);
    }
    const modalEl = document.getElementById('terlaporModal');
    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
    document.getElementById('tlp-name').value = '';
    document.getElementById('tlp-address').value = '';
    document.getElementById('tlp-phone').value = '';
    unitType.value = '';
    toggleUnitFields();
  });
});
</script>
@endsection
