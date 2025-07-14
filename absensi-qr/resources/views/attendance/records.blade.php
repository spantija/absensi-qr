@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Rekaman Kehadiran</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $record)
                <tr>
                    <td>{{ $record->date }}</td>
                    <td>{{ $record->student->name ?? '-' }}</td>
                    <td>{{ $record->classroom->name ?? '-' }}</td>
                    <td>{{ $record->subject->name ?? '-' }}</td>
                    <td>{{ ucfirst($record->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $attendances->links() }}
</div>
@endsection
