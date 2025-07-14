@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Scan QR untuk Absen</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row justify-content-center">
        {{-- Tombol Scan Masuk --}}
        <div class="col-md-4 text-center mb-3">
            <a href="{{ route('attendance.scan.masuk') }}" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Scan Masuk
            </a>
        </div>

        {{-- Tombol Scan Pulang --}}
        <div class="col-md-4 text-center mb-3">
            <a href="{{ route('attendance.scan.pulang') }}" class="btn btn-success btn-lg w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Scan Pulang
            </a>
        </div>
    </div>
</div>
@endsection
