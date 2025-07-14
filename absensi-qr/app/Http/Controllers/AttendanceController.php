<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use Illuminate\Support\Facades\Response;
use App\Helpers\FonnteHelper;

use App\Models\Student;

class AttendanceController extends Controller
{
    public function rekapMingguan()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $rekap = Attendance::with('student')
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('date')
            ->get()
            ->groupBy(function ($item) {
                return $item->student->name;
            });

        return view('rekap-mingguan', compact('rekap', 'startOfWeek', 'endOfWeek'));
    }

    public function rekapBulanan()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $rekap = Attendance::with('student')
            ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->orderBy('date')
            ->get()
            ->groupBy(function ($item) {
                return $item->student->name;
            });

        return view('rekap-bulanan', compact('rekap', 'startOfMonth', 'endOfMonth'));
    }

    public function rekapTahunan()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $rekap = Attendance::with('student')
            ->whereBetween('date', [$startOfYear->toDateString(), $endOfYear->toDateString()])
            ->orderBy('date')
            ->get()
            ->groupBy(function ($item) {
                return $item->student->name;
            });

        return view('rekap-tahunan', compact('rekap', 'startOfYear', 'endOfYear'));
    }

        public function exportTahunan()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();

        $data = Attendance::with('student')
            ->whereBetween('date', [$startOfYear->toDateString(), $endOfYear->toDateString()])
            ->orderBy('date')
            ->get();

        return Excel::download(new AttendanceExport($data), 'rekap-absensi-tahunan.xlsx');
    }

    public function exportMingguan()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = Attendance::with('student')
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('date')
            ->get();

        return Excel::download(new AttendanceExport($data), 'rekap-absensi-mingguan.xlsx');
    }

   public function store(Request $request)
    {
        $request->validate([
            'qr_code_id' => 'required|string',
        ]);

        $student = \App\Models\Student::where('qr_code_id', $request->qr_code_id)->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak ditemukan.'
            ], 404);
        }

        $today = now()->toDateString();

        $alreadyExists = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'status' => 'info',
                'message' => 'Siswa sudah absen hari ini.'
            ]);
        }

        Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'status' => 'Hadir',
            'recorded_by' => Auth::id(), // ID petugas (jika menggunakan login)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi siswa berhasil dicatat.',
            'student' => $student->name,
            'petugas' => Auth::user()->name ?? 'Petugas',
            'time' => now()->format('H:i:s'),
        ]);
    }

    public function formInput()
    {
        return view('attendance.input');
    }

    public function scan()
    {
        return view('attendance.scan');
    }

    public function form()
    {
        return view('attendance.form');
    }

    public function checkin(Request $request)
    {
        $student = Student::find($request->student_id);

        if (!$student) {
            return back()->with('error', 'Siswa tidak ditemukan.');
        }

        // Simpan absensi
        Attendance::create([
            'student_id' => $student->id,
            'type' => 'masuk',
            'time' => now(),
        ]);

        // Kirim WA ke wali murid
        if ($student->parent_phone) {
            $message = "🟢 Absen MASUK:\nSiswa: {$student->name}\nWaktu: " . now()->format('H:i d-m-Y');
            FonnteHelper::send($student->parent_phone, $message);
        }

        return back()->with('success', 'Absen masuk berhasil.');
    }

    public function checkOut(Request $request)
    {
        $studentId = $request->input('student_id');

        $attendance = Attendance::where('student_id', $studentId)
            ->whereDate('date', now()->toDateString())
            ->first();

        if (!$attendance || $attendance->check_out) {
            return back()->with('error', 'Belum absen masuk atau sudah absen pulang.');
        }

        $attendance->update(['check_out' => now()->format('H:i:s')]);

        return back()->with('success', 'Absen pulang berhasil.');
    }

    public function scanMasuk()
    {
        return view('attendance.scan', ['type' => 'masuk']);
    }

    public function scanPulang()
    {
        return view('attendance.scan', ['type' => 'pulang']);
    }

    public function scanHomeroom()
    {
        return view('attendance.scan_homeroom');
    }

    public function homeroomCheckin(Request $request)
    {
        $student = Student::findOrFail($request->student_id);

        // Pastikan guru adalah wali kelas siswa tersebut
        $user = Auth::user();
        if ($user->class_id !== $student->class_id) {
            return back()->with('error', 'Siswa ini bukan dari kelas Anda.');
        }

        // Simpan absensi masuk
        Attendance::create([
            'student_id' => $student->id,
            'status' => 'masuk',
            'recorded_by' => $user->id, // ID guru
            'created_at' => now(),
        ]);

        return back()->with('success', 'Absensi berhasil direkam.');
    }

    public function subjectScan(Subject $subject)
    {
        // pastikan subject ini memang diajar guru ini
        if (!auth()->user()->subjects->contains($subject)) {
            abort(403);
        }

        return view('attendance.subject-scan', [
            'subject' => $subject
        ]);
    }

    public function downloadClassRecap()
    {
        $user = Auth::user();

        if (!$user->is_homeroom_teacher || !$user->class_id) {
            return redirect()->back()->with('error', 'Anda bukan wali kelas.');
        }

        $students = Student::where('class_id', $user->class_id)->get();
        $attendances = Attendance::whereIn('student_id', $students->pluck('id'))
                        ->with(['student'])
                        ->orderBy('date', 'asc')
                        ->get();

        // Format CSV
        $csvHeader = "Nama Siswa,Tanggal,Status,Jam Masuk,Jam Pulang\n";
        $csvBody = "";

        foreach ($attendances as $attendance) {
            $csvBody .= "{$attendance->student->name},{$attendance->date},{$attendance->status},{$attendance->check_in},{$attendance->check_out}\n";
        }

        $filename = 'rekap_absensi_kelas_' . now()->format('Ymd_His') . '.csv';

        return Response::make($csvHeader . $csvBody, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function downloadSubjectRekap(Subject $subject)
    {
        // Pastikan guru hanya bisa mengakses mapel yang diajarkan
        if (!Auth::user()->subjects->contains($subject)) {
            abort(403);
        }

        $attendances = Attendance::with('student', 'class')
            ->where('subject_id', $subject->id)
            ->orderBy('date')
            ->get();

        $csv = "Tanggal,Nama Siswa,Kelas,Status\n";

        foreach ($attendances as $att) {
            $csv .= "{$att->date},{$att->student->name},{$att->class->name},{$att->status}\n";
        }

        $filename = "rekap-absen-{$subject->name}.csv";

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function records(Request $request)
{
    $attendances = Attendance::with(['student', 'subject', 'classroom'])
        ->latest()
        ->paginate(30);

    return view('attendance.records', compact('attendances'));
}
    
    }