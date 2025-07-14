@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><i class="fas fa-users"></i> Daftar Siswa</h4>
        <div class="btn-group">
            <a href="{{ route('students.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Siswa
            </a>
            <a href="{{ route('students.import') }}" class="btn btn-secondary">
                <i class="fas fa-file-upload"></i> Import
            </a>
            <a href="{{ route('students.template') }}" class="btn btn-outline-secondary">
                <i class="fas fa-download"></i> Template
            </a>
        </div>
    </div>

    {{-- Filter Kelas --}}
    <form method="GET" action="{{ route('students.index') }}" class="mb-3">
        <div class="input-group">
            <select name="class_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Kelas --</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary" type="submit">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>QR Code</th>
                <th>Kelas</th>
                <th>Wali Murid</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Alamat</th>
                <th>Agama</th>
                <th>No. Telepon</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <td>
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto" width="50">
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->nisn }}</td>
                    <td>{{ $student->qr_code_id }}</td>
                    <td>{{ $student->classroom->name ?? '-' }}</td>
                    <td>{{ $student->walimurid->name ?? '-' }}</td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->birth_place }}, {{ \Carbon\Carbon::parse($student->birth_date)->format('d-m-Y') }}</td>
                    <td>{{ $student->address }}</td>
                    <td>{{ $student->religion }}</td>
                    <td>{{ $student->phone }}</td>
                    <td>{{ ucfirst($student->status) }}</td>
                    <td>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('students.card', $student->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-id-card"></i>
                        </a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center">Belum ada data siswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $students->links() }}
    </div>
</div>
@endsection
