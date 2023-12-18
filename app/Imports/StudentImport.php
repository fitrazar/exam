<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $grade = 0;
        $major = 0;
        $parts = explode(' ', $row[4]);
        if ($parts[0] === "X") {
            $grade = 1;
        } elseif ($parts[0] === "XI") {
            $grade = 2;
        } elseif ($parts[0] === "XII") {
            $grade = 3;
        }

        if ($parts[1] === "TKJ" || $parts[1] === "TJKT") {
            $major = 1;
        } elseif ($parts[1] === "DPIB") {
            $major = 2;
        } elseif ($parts[1] === "TBSM") {
            $major = 3;
        }

        return new Student([
            'name' => Str::title($row[1]),
            'gender' => $row[2] == 'L' ? 'Laki - Laki' : 'Perempuan',
            'nisn' => $row[3],
            'phone' => '-',
            'point' => 100,
            'grade_id' => $grade,
            'major_id' => $major,
            'group_id' => $parts[2],
            'password' => bcrypt($row[3]),
        ]);
    }

    public function headingRow(): int
    {
        return 2;
    }
}
