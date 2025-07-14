<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Attendance;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WalimuridController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\StudentTemplateController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\TeacherImportController;
use App\Http\Controllers\InformationController;
use App\Models\SchoolInfo;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========== PUBLIC ==========

Route::get('information/{id}', [InformationController::class, 'show'])->name('information.show');
Route::get('/information/{id}', [App\Http\Controllers\InformationController::class, 'show'])->name('information.show');

Route::get('/', function () {
    $infos = SchoolInfo::orderBy('date', 'desc')->take(5)->get();
    return view('welcome', compact('infos'));
});


// ========== AUTH + VERIFIED ==========
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (Redirect handled by middleware)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/classes', [ClassroomController::class, 'index'])->name('classes.index')->middleware(['auth', 'can:admin']);
Route::post('/classes/{id}/update-walikelas', [ClassroomController::class, 'updateWaliKelas'])->name('classes.updateWaliKelas')->middleware(['auth', 'can:admin']);


//route admin
Route::middleware(['auth', 'can:admin'])->group(function () {
    Route::resource('/admin/informations', InformationController::class);
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create'); // <== tambahkan ini
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    
    Route::resource('school-infos', SchoolInfoController::class);
    Route::resource('information', InformationController::class);

    Route::resource('subjects', SubjectController::class);
    Route::post('subjects/{subject}/assign-teachers', [SubjectController::class, 'assignTeachers'])->name('subjects.assignTeachers');
    Route::post('/teaching-assignments', [TeachingAssignmentController::class, 'store'])->name('teaching.assign');
    Route::get('subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');

    Route::get('/rekap', function () {
        $rekap = Attendance::with('student')->orderBy('date', 'desc')->paginate(20);
        return view('rekap', compact('rekap'));
    })->name('rekap');

    Route::get('/rekap-mingguan', [AttendanceController::class, 'rekapMingguan']);
    Route::get('/rekap-bulanan', [AttendanceController::class, 'rekapBulanan']);
    Route::get('/rekap-tahunan', [AttendanceController::class, 'rekapTahunan']);
    Route::get('/rekap-export-mingguan', [AttendanceController::class, 'exportMingguan']);
    Route::get('/rekap-export-tahunan', [AttendanceController::class, 'exportTahunan']);

    Route::get('/rekap-mingguan', [AttendanceController::class, 'rekapMingguan'])->name('rekap.mingguan');
    Route::get('/rekap-bulanan', [AttendanceController::class, 'rekapBulanan'])->name('rekap.bulanan');
    Route::get('/rekap-tahunan', [AttendanceController::class, 'rekapTahunan'])->name('rekap.tahunan');

    Route::get('academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
    Route::get('academic-years/create', [AcademicYearController::class, 'create'])->name('academic-years.create');
    Route::post('academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
    Route::post('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
    
    Route::get('/statistics/attendance', [StatisticsController::class, 'attendance'])
    ->middleware(['auth', 'can:admin']) // cukup auth dan gate
    ->name('statistics.attendance');

    Route::get('/teachers/import', [TeacherImportController::class, 'showForm'])->name('teachers.import.form');

    Route::post('/teachers/import', [TeacherImportController::class, 'import'])->name('teachers.import');
    Route::get('/teachers/template', [TeacherImportController::class, 'template'])->name('teachers.template');

});

// Untuk guru
Route::middleware(['auth', 'can:guru'])->group(function () {
    Route::get('/attendance/form', [AttendanceController::class, 'form'])->name('attendance.form');

    Route::get('/attendance/scan/masuk', [AttendanceController::class, 'scanMasuk'])->name('attendance.scan.masuk');
    Route::get('/attendance/scan/pulang', [AttendanceController::class, 'scanPulang'])->name('attendance.scan.pulang');

    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');

    Route::get('/attendance/homeroom/scan', [App\Http\Controllers\AttendanceController::class, 'scanHomeroom'])
        ->name('attendance.homeroom.scan');

    Route::post('/attendance/homeroom/checkin', [App\Http\Controllers\AttendanceController::class, 'homeroomCheckin'])
        ->name('attendance.homeroom.checkin');

    Route::get('/attendance/subject-scan/{subject}', [AttendanceController::class, 'subjectScan'])
    ->name('attendance.subject.scan')
    ->middleware(['auth', 'can:guru']);

});

// Tampilkan profil
// Proses update profil



// ========== AUTH ==========
Route::middleware('auth')->group(function () {

        // Halaman untuk melihat data kehadiran oleh guru/petugas
    Route::get('/attendance/records', [AttendanceController::class, 'records'])
        ->name('attendance.records');

    // ========== WALIMURID ==========
        Route::get('/tautkan-siswa', [App\Http\Controllers\WalimuridController::class, 'tautkanForm'])->name('tautkan.form');
        Route::post('/tautkan-siswa', [App\Http\Controllers\WalimuridController::class, 'tautkanSimpan'])->name('tautkan.store');
        Route::get('/statistik-anak', [WalimuridController::class, 'statistics'])
        ->name('walimurid.statistics')
        ->middleware(['auth', 'can:walimurid']);

    // ========== QR SCAN ==========
    Route::view('/scan', 'scan');
    Route::post('/scan-absen', function (Request $request) {
        $qrId = $request->qr_code_id;

        $student = Student::where('qr_code_id', $qrId)->first();
        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $sudahAbsen = Attendance::whereDate('date', now()->toDateString())
            ->where('student_id', $student->id)
            ->where('type', 'masuk')
            ->exists();

        if ($sudahAbsen) {
            return response()->json(['message' => 'Siswa sudah absen hari ini']);
        }

        Attendance::create([
            'student_id' => $student->id,
            'date' => now()->toDateString(),
            'time' => now()->toTimeString(),
            'type' => 'masuk',
            'input_by' => auth()->id(),
            'class' => $student->class
        ]);

        return response()->json(['message' => 'Absensi berhasil dicatat!']);
    });

    // ========== REKAP ==========
    
    // ========== ABSENSI ANAK ==========
    Route::get('/absensi-anak', function () {
        $user = auth()->user();
        $siswa = Student::where('walimurid_id', $user->id)->first();
        $absensi = $siswa ? Attendance::where('student_id', $siswa->id)->get() : [];
        return view('absensi-anak', compact('siswa', 'absensi'));
    });

    // ========== PROFILE ==========
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile'); // <--- INI YANG BELUM ADA
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // ========== STUDENTS ==========
    

    Route::get('/students/import', [StudentImportController::class, 'showImportForm'])->name('students.import.form');
    Route::post('/students/import', [StudentImportController::class, 'import'])->name('students.import');
    Route::get('/students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');
    Route::get('/template-siswa', [StudentTemplateController::class, 'download'])->name('students.template');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::get('/students/{id}/card', [StudentController::class, 'downloadCard'])->name('students.card');

    Route::get('/attendance/input', [AttendanceController::class, 'formInput'])->name('attendance.input');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');

    Route::get('/attendance/subject-scan/{subject}', [AttendanceController::class, 'subjectScan'])
    ->name('attendance.subject.scan')
    ->middleware(['auth', 'can:guru']);

    Route::get('/attendance/recap/class', [AttendanceController::class, 'downloadClassRecap'])
    ->name('attendance.class.recap')
    ->middleware(['auth', 'can:guru']);

    Route::get('/rekap/mapel/{subject}', [AttendanceController::class, 'downloadSubjectRekap'])
    ->name('attendance.rekap.subject')
    ->middleware(['auth', 'can:guru']);

    Route::resource('students', StudentController::class);
});



require __DIR__.'/auth.php';
