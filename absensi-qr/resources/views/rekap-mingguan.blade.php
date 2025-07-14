@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Rekap Absensi Mingguan</h2>
        <span class="text-muted">{{ $startOfWeek->format('d M') }} - {{ $endOfWeek->format('d M Y') }}</span>
    </div>

    @foreach ($rekap as $siswa => $absens)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $siswa }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absens as $absen)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($absen->date)->format('d M Y') }}</td>
                                <td>{{ $absen->time }}</td>
                                <td>{{ ucfirst($absen->type) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <div class="mt-4">
        <a href="/" class="btn btn-secondary">Kembali</a>
        <a href="{{ url('/rekap-export') }}" class="btn btn-success">Export Excel</a>
    </div>
</div>
@endsection