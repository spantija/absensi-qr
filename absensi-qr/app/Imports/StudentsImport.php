<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati baris kosong
        if (empty($row['name'])) {
            return null;
        }

        $classroom = Classroom::firstOrCreate(['name' => $row['classes']]);

        return new Student([
            'name'         => $row['name'],
            'qr_code_id'   => Str::random(10),
            'class_id' => $classroom ? $classroom->id : null,
            'nisn'         => $row['nisn'],
            'gender'       => $row['gender'],
            'birth_place'  => $row['birth_place'],
            'birth_date'   => $this->parseDate($row['birth_date']),
            'address'      => $row['address'],
            'religion'     => $row['religion'],
            'phone'        => $row['phone'],
            'status'       => $row['status'],
        ]);
    }

    private function parseDate($date)
    {
        return Carbon::parse($date);
    }
}
