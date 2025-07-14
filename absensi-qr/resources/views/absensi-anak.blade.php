@if ($siswa)
    <h2>Riwayat Absensi {{ $siswa->name }}</h2>
    <ul>
        @foreach ($absensi as $row)
            <li>{{ $row->date }} - {{ $row->time }} - {{ $row->type }}</li>
        @endforeach
    </ul>
@else
    <h2>Data siswa tidak ditemukan untuk akun ini.</h2>
@endif
