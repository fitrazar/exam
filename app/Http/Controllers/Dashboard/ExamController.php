<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::all();
        return view('dashboard.exam.index', compact('exams'));
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

        return view('dashboard.exam.create', compact('rombels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'date_start' => 'required',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'rombel' => 'required'
        ]);

        list($gradeId, $majorId, $groupId) = explode(' ', $validatedData['rombel']);

        Exam::create([
            'grade_id' => $gradeId,
            'major_id' => $majorId,
            'group_id' => $groupId,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'date_start' => $validatedData['date_start'],
            'time_start' => $validatedData['time_start'],
            'time_end' => $validatedData['time_end'],
        ]);

        return redirect('/dashboard/exam')->with('success', 'Ujian Berhasil Ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
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

        return view('dashboard.exam.edit', compact('exam', 'rombels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $rules = [
            'title' => 'required|string',
            'description' => 'required|string',
            'date_start' => 'required',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'rombel' => 'required'
        ];

        $validatedData = $request->validate($rules);

        list($gradeId, $majorId, $groupId) = explode(' ', $validatedData['rombel']);

        Exam::findOrFail($exam->id)->update([
            'grade_id' => $gradeId,
            'major_id' => $majorId,
            'group_id' => $groupId,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'date_start' => $validatedData['date_start'],
            'time_start' => $validatedData['time_start'],
            'time_end' => $validatedData['time_end'],
        ]);

        return redirect('/dashboard/exam')->with('success', 'Ujian Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        Exam::destroy($exam->id);
        return redirect('/dashboard/exam')->with('success', 'Ujian Berhasil Dihapus');
    }
}
