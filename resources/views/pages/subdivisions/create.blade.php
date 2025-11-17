@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0" style="color: #fff;">{{ isset($subdivision) ? 'Edit Sub Unit' : 'Tambah Sub Unit' }}</h5>
                </div>
                <div class="card-body">
                    <form 
                        action="{{ isset($subdivision) ? route('subdivisions.update', $subdivision->id) : route('subdivisions.store') }}" 
                        method="POST"
                    >
                        @csrf
                        @if(isset($subdivision))
                            @method('PUT')
                        @endif

                        <!-- Parent Division -->
                        <div class="form-group mb-3">
                            <label>Unit Induk</label>
                            <select name="parent_id" class="form-control select2">
                                <option value="">-- Pilih Unit Induk --</option>
                                @foreach ($parentDivisions as $parent)
                                    <option value="{{ $parent->id }}" 
                                        {{ old('parent_id', $subdivision->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nama Sub Divisi -->
                        <div class="form-group mb-3">
                            <label>Nama Unit</label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control" 
                                value="{{ old('name', $subdivision->name ?? '') }}" 
                                required
                            >
                        </div>

                        <div class="form-group mb-3">
                            <label>Level</label>
                            @php($currentLevel = strtolower(old('level', $subdivision->level ?? '')))
                            <select name="level" class="form-control" required>
                                <option value="">-- Pilih Level --</option>
                                <option value="polda" {{ $currentLevel === 'polda' ? 'selected' : '' }}>Polda</option>
                                <option value="polres" {{ $currentLevel === 'polres' ? 'selected' : '' }}>Polres</option>
                                <option value="polsek" {{ $currentLevel === 'polsek' ? 'selected' : '' }}>Polsek</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Tipe</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="satker" {{ old('type', $subdivision->type ?? '') == 'satker' ? 'selected' : '' }}>Satker</option>
                                <option value="satwil" {{ old('type', $subdivision->type ?? '') == 'satwil' ? 'selected' : '' }}>Satwil</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Permissions</label>
                            @php($perms = is_array(old('permissions', isset($subdivision->permissions) ? json_decode($subdivision->permissions, true) : [])) ? old('permissions', json_decode($subdivision->permissions ?? '[]', true)) : [])
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm-inspection" name="permissions[inspection]" value="1" {{ !empty($perms['inspection']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-inspection">Inspection</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="perm-investigation" name="permissions[investigation]" value="1" {{ !empty($perms['investigation']) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm-investigation">Investigation</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">
                            {{ isset($subdivision) ? 'Update' : 'Simpan' }}
                        </button>
                        <a href="{{ route('subdivisions.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
