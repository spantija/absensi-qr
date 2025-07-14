<h2>Rekap Absensi</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Jenis</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($rekap as $row)
        <tr>
            <td>{{ $row->student->name }}</td>
            <td>{{ $row->class }}</td>
            <td>{{ $row->date }}</td>
            <td>{{ $row->time }}</td>
            <td>{{ $row->type }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
