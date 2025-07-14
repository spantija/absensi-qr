@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h4>Scan QR Siswa (Wali Kelas - Masuk)</h4>

    <video id="preview" width="100%" class="border rounded shadow-sm my-3"></video>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="attendanceForm" method="POST" action="{{ route('attendance.homeroom.checkin') }}">
        @csrf
        <input type="hidden" name="student_id" id="student_id">
    </form>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    const scanner = new Html5Qrcode("preview");

    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('student_id').value = decodedText;
        document.getElementById('attendanceForm').submit();
        scanner.stop();
    }

    Html5Qrcode.getCameras().then(devices => {
        if (devices.length) {
            scanner.start(devices[0].id, { fps: 10, qrbox: 250 }, onScanSuccess);
        }
    }).catch(err => {
        alert("Gagal mengakses kamera: " + err);
    });
</script>
@endsection
