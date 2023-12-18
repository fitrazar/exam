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

        $exam = Exam::create([
            'grade_id' => $gradeId,
            'major_id' => $majorId,
            'group_id' => $groupId,
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'date_start' => $validatedData['date_start'],
            'time_start' => $validatedData['time_start'],
            'time_end' => $validatedData['time_end'],
        ]);

        $types = $request->input('type');
        $questions = $request->input('question');
        $option_1 = $request->input('option_1');
        $option_2 = $request->input('option_2');
        $option_3 = $request->input('option_3');
        $option_4 = $request->input('option_4');
        $option_5 = $request->input('option_5');
        $answers = $request->input('answer');

        $dataArray = [];
        $numberOfItems = count($questions);
        $scorePerQuestion = 100 / $numberOfItems;
        for ($i = 0; $i < $numberOfItems; $i++) {
            $answer = $answers[$i];

            if (strpos($answer, '|') !== false) {
                $answersArray = explode('|', $answer);
            } else {
                $answersArray = [$answer];
            }
            $dataArray[] = [
                'type' => $types[$i],
                'question' => $questions[$i],
                'option_1' => $option_1[$i],
                'option_2' => $option_2[$i],
                'option_3' => $option_3[$i],
                'option_4' => $option_4[$i],
                'option_5' => $option_5[$i],
                'answer' => $answersArray,
                'score' => number_format($scorePerQuestion, 1),
            ];
        }

        foreach ($dataArray as $key => $qa) {
            if ($qa['type'] == 0 || $qa['type'] == 1) {
                $qst = $qa['question'] . "\nA. " . $qa['option_1'] . "\nB. " . $qa['option_2'] . "\nC. " . $qa['option_3'] . "\nD. " . $qa['option_4'] . "\nE. " . $qa['option_5'];
            } else {
                $qst = $qa['question'];
            }
            $question = $exam->questions()->create([
                'exam_id' => $exam->id,
                'question' => $qst,
                'score' => intval($qa['score']),
                'type' => $qa['type'],
                'is_required' => 1
            ]);

            foreach ($qa['answer'] as $an) {
                $question->answerOptions()->create([
                    'question_id' => $question->id,
                    'option' => $an,
                ]);
            }
            // dd($qa);
        }


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
