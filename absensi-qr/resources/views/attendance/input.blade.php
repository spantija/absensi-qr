@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Input Absensi oleh Petugas</h4>

    @if(session('message'))
        <div class="alert alert-{{ session('status') }}">{{ session('message') }}</div>
    @endif

    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="qr_code_id" class="form-label">QR Code Siswa</label>
            <input type="text" name="qr_code_id" id="qr_code_id" class="form-control" placeholder="Contoh: QR-8JHDK3PW" required>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Simpan Absensi
        </button>
    </form>
</div>
@endsection
