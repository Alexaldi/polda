@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <h3>{{ $report['title'] }}</h3>
      <p><b>Status:</b> {{ $report['status'] }}</p>
      <p><b>Kategori:</b> {{ $report['category'] }}</p>
      <p><b>Alamat:</b> {{ $report['address'] }}</p>
      <p class="mt-3">{{ $report['description'] }}</p>

      <hr>

      {{-- ALERT FEEDBACK --}}
      @if(session('success'))
        <div class="alert alert-success mt-3">
          {{ session('success') }}
        </div>
      @endif

      {{-- JOURNEY SECTION --}}
      <h5 class="mt-4">Journey Laporan</h5>
      <p class="text-muted">Belum ada data journey. (sementara dummy)</p>

      {{-- FORM DUMMY UNTUK TEST ROUTE --}}
      <form action="{{ route('journeys.store', $report['id']) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
          + Tambah Journey (Dummy Test)
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
