<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Major;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return view('dashboard.student.index', [
            'students' => $students
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::pluck('name', 'id');
        $majors = Major::pluck('acronym', 'id');
        $groups = Group::pluck('number', 'id');

        $rombels = [];
        foreach ($grades as $gradeId => $gradeName) {
            foreach ($majors as $majorId => $majorName) {
                foreach ($groups as $groupId => $groupName) {
                    $rombels[] = [
                        'id' => "{$gradeId} {$majorId} {$groupId}",
                        'name' => "{$gradeName} {$majorName} {$groupName}"
                    ];
                }
            }
        }

        return view('dashboard.student.create', compact('rombels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nisn' => 'required|numeric',
            'name' => 'required|string',
            'gender' => 'required|in:Laki - Laki,Perempuan',
            'phone' => 'required|numeric',
            'rombel' => 'required'
        ]);

        list($gradeId, $majorId, $groupId) = explode(' ', $validatedData['rombel']);

        Student::create([
            'grade_id' => $gradeId,
            'major_id' => $majorId,
            'group_id' => $groupId,
            'nisn' => $validatedData['nisn'],
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone' => $validatedData['phone'],
            'point' => 100,
            'password' => bcrypt($validatedData['nisn']),
        ]);

        return redirect('/dashboard/student')->with('success', 'Siswa Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $grades = Grade::pluck('name', 'id');
        $majors = Major::pluck('acronym', 'id');
        $groups = Group::pluck('number', 'id');

        $rombels = [];
        foreach ($grades as $gradeId => $gradeName) {
            foreach ($majors as $majorId => $majorName) {
                foreach ($groups as $groupId => $groupName) {
                    $rombels[] = [
                        'id' => "{$gradeId} {$majorId} {$groupId}",
                        'name' => "{$gradeName} {$majorName} {$groupName}"
                    ];
                }
            }
        }

        return view('dashboard.student.edit', compact('student', 'rombels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $rules = [
            'nisn' => 'required|numeric',
            'name' => 'required|string',
            'gender' => 'required|in:Laki - Laki,Perempuan',
            'phone' => 'required|numeric',
            'rombel' => 'required'
        ];

        $validatedData = $request->validate($rules);

        list($gradeId, $majorId, $groupId) = explode(' ', $validatedData['rombel']);

        Student::findOrFail($student->id)->update([
            'grade_id' => $gradeId,
            'major_id' => $majorId,
            'group_id' => $groupId,
            'nisn' => $validatedData['nisn'],
            'name' => $validatedData['name'],
            'gender' => $validatedData['gender'],
            'phone' => $validatedData['phone'],
            'password' => bcrypt($validatedData['nisn']),
        ]);

        return redirect('/dashboard/student')->with('success', 'Siswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        Student::destroy($student->id);
        return redirect('/dashboard/student')->with('success', 'Siswa Berhasil Dihapus');
    }

    public function import(Request $request)
    {
        $validator = $request->validate([
            'file' => 'file|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        // upload ke folder file_siswa di dalam folder public
        $validator['file'] = $file->store('files');
        Excel::import(new StudentImport, request()->file('file'));

        return redirect()->back()->with('success', 'Data Berhasil di Import');
    }
}
