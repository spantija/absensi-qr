@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Tautkan Akun Wali Murid dengan Siswa</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('tautkan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="qr_code_id" class="form-label">Kode QR Siswa</label>
            <input type="text" name="qr_code_id" id="qr_code_id" class="form-control" placeholder="Masukkan Kode QR yang tercetak di kartu siswa" required>
            @error('qr_code_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Tautkan Siswa</button>
    </form>
</div>
@endsection
