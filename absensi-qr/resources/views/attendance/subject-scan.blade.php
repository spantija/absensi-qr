@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
    <h4>Scan Absensi Mapel: {{ $subject->name }}</h4>

    <video id="preview" width="100%" class="border rounded shadow-sm my-3"></video>

    <form id="attendanceForm" method="POST" action="{{ route('attendance.input') }}">
        @csrf
        <input type="hidden" name="student_id" id="student_id">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
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
        console.error("Camera error: ", err);
    });
</script>
@endsection
