<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom; // gunakan nama model, bukan nama tabel
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Subject;


class StudentController extends Controller
{
public function downloadCard($id)
{
    $student = Student::with('classroom')->findOrFail($id);
    $pdf = Pdf::loadView('students.card', compact('student'));
    return $pdf->download('kartu_pelajar_' . $student->name . '.pdf');
}

public function index(Request $request)
{
    $classes = Classroom::all();

    $query = Student::with('classroom');

    if ($request->filled('class_id')) {
        $query->where('class_id', $request->class_id);
    }

    $students = $query->paginate(20);

    return view('students.index', compact('students', 'classes'));
}


public function create()
{
    $classes = Classroom::all();
    $walimurid = User::where('role', 'walimurid')->get();
    $subjects = Subject::with('teachers')->get(); // Tambahkan ini jika form siswa juga menampilkan data mapel

    return view('students.create', compact('classes', 'walimurid', 'subjects'));
}


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            // tambahkan validasi sesuai kebutuhan
        ]);

        $qrCode = 'QR-' . strtoupper(Str::random(8));

        Student::create([
            'name' => $request->name,
            'qr_code_id' => $qrCode,
            'class_id' => $request->class_id,
            'walimurid_id' => $request->walimurid_id,
            'nisn' => $request->nisn,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'religion' => $request->religion,
            'phone' => $request->phone,
            
        ]);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = Classroom::all();
        $walimurid = User::where('role', 'walimurid')->get();

        return view('students.edit', compact('student', 'classes', 'walimurid'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            // tambahkan validasi sesuai kebutuhan
        ]);

        $student = Student::findOrFail($id);
        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Student::findOrFail($id)->delete();
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
