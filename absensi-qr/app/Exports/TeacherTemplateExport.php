<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TeacherTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return []; // kosong, hanya template
    }

    public function headings(): array
    {
        return [
            'name',
            'phone', // opsional
        ];
    }
}
