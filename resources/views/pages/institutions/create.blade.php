@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8 offset-xl-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-building me-2"></i>{{ isset($institution) ? 'Edit Institusi' : 'Tambah Institusi' }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($institution) ? route('institutions.update', $institution->id) : route('institutions.store') }}">
                        @csrf
                        @if(isset($institution))
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Institusi <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $institution->name ?? '') }}" required>
                                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tipe Institusi <span class="text-danger">*</span></label>
                                <select name="type" class="form-control" required>
                                    <option value="">Pilih Tipe Institusi</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}" @selected(old('type', $institution->type ?? '') === $type)>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">{{ isset($institution) ? 'Update' : 'Simpan' }}</button>
                            <a href="{{ route('institutions.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
