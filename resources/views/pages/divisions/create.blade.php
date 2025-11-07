@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-6 offset-xl-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ isset($division) ? 'Edit Divisi' : 'Tambah Divisi' }}</h5>
                </div>
                <div class="card-body">
                    <form 
                        action="{{ isset($division) ? route('divisions.update', $division->id) : route('divisions.store') }}" 
                        method="POST"
                    >
                        @csrf
                        @if(isset($division))
                            @method('PUT')
                        @endif

                        <div class="form-group mb-3">
                            <label>Nama Divisi</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                value="{{ old('name', $division->name ?? '') }}" 
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label>Tipe Divisi</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="polres" {{ (old('type', $division->type ?? '') == 'polres') ? 'selected' : '' }}>Polres</option>
                                <option value="satuan" {{ (old('type', $division->type ?? '') == 'satuan') ? 'selected' : '' }}>Satuan</option>
                                <option value="subbag" {{ (old('type', $division->type ?? '') == 'subbag') ? 'selected' : '' }}>Subbag</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Parent Divisi</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- Tidak ada --</option>
                                @foreach($parentDivisions as $parent)
                                    <option 
                                        value="{{ $parent->id }}" 
                                        {{ (old('parent_id', $division->parent_id ?? '') == $parent->id) ? 'selected' : '' }}
                                    >
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            {{ isset($division) ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('divisions.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
