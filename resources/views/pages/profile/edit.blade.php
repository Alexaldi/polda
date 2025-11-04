@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-6">
      <div class="card">
        <div class="card-header"><h5>Edit Profil</h5></div>
        <div class="card-body">
          <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PUT')

            <div class="mb-3">
              <label>Nama</label>
              <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
            </div>
            <div class="mb-3">
              <label>Instansi</label>
              <select name="institution_id" class="form-select select2">
                <option value="">-- Pilih Instansi --</option>
                @foreach($institutions as $inst)
                  <option value="{{ $inst->id }}" {{ $user->institution_id == $inst->id ? 'selected' : '' }}>
                    {{ $inst->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label>Divisi</label>
              <select name="division_id" class="form-select select2">
                <option value="">-- Pilih Divisi --</option>
                @foreach($divisions as $div)
                  <option value="{{ $div->id }}" {{ $user->division_id == $div->id ? 'selected' : '' }}>
                    {{ $div->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-xl-6">
      <div class="card">
        <div class="card-header"><h5>Ubah Password</h5></div>
        <div class="card-body">
          <form method="POST" action="{{ route('profile.password.update') }}">
            @csrf @method('PUT')
            <div class="mb-3">
              <label>Password Lama</label>
              <input type="password" name="current_password" class="form-control">
            </div>
            <div class="mb-3">
              <label>Password Baru</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
              <label>Konfirmasi Password</label>
              <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button class="btn btn-warning">Update Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
