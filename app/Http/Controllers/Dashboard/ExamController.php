<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Exam;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Major;
use App\Models\Question;
use App\Imports\QnAImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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
            'code' => mt_rand(10000, 99999),
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

            $dataArray[] = [
                'type' => $types[$i],
                'question' => $questions[$i],
                'option_1' => $option_1[$i],
                'option_2' => $option_2[$i],
                'option_3' => $option_3[$i],
                'option_4' => $option_4[$i],
                'option_5' => $option_5[$i],
                'answer' => $answers[$i],
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

            $question->answerOptions()->create([
                'question_id' => $question->id,
                'option' => $qa['answer'],
            ]);

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

        $questions = Question::with('answerOptions')->where('exam_id', $exam->id)->get();

        return view('dashboard.exam.edit', compact('exam', 'rombels', 'questions'));
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


        $types = $request->input('type');
        $questions = $request->input('question');
        $option_1 = $request->input('option_1');
        $option_2 = $request->input('option_2');
        $option_3 = $request->input('option_3');
        $option_4 = $request->input('option_4');
        $option_5 = $request->input('option_5');
        $answers = $request->input('answer');
        $qa_id = $request->input('id_qa');


        $dataArray = [];
        $numberOfItems = count($questions);
        $scorePerQuestion = 100 / $numberOfItems;
        for ($i = 0; $i < $numberOfItems; $i++) {
            $dataArray[] = [
                'type' => $types[$i],
                'question' => $questions[$i],
                'option_1' => $option_1[$i],
                'option_2' => $option_2[$i],
                'option_3' => $option_3[$i],
                'option_4' => $option_4[$i],
                'option_5' => $option_5[$i],
                'answer' => $answers[$i],
                'score' => number_format($scorePerQuestion, 1),
                'qa_id' => $qa_id[$i] ?? '',
            ];
        }

        foreach ($dataArray as $key => $qa) {
            if ($qa['type'] == 0 || $qa['type'] == 1) {
                $qst = $qa['question'] . "\nA. " . $qa['option_1'] . "\nB. " . $qa['option_2'] . "\nC. " . $qa['option_3'] . "\nD. " . $qa['option_4'] . "\nE. " . $qa['option_5'];
            } else {
                $qst = $qa['question'];
            }
            if ($qa['qa_id'] != '') {
                $question = Question::find($qa['qa_id']);

                if ($question) {

                    $question->update([
                        'question' => $qst,
                        'score' => intval($qa['score']),
                        'type' => $qa['type'],
                        'is_required' => 1,
                    ]);

                    $answerOption = $question->answerOptions()->first();
                    if ($answerOption) {
                        $answerOption->update([
                            'option' => $qa['answer'],
                        ]);
                    }
                }
            } else {
                $question = $exam->questions()->create([
                    'exam_id' => $exam->id,
                    'question' => $qst,
                    'score' => intval($qa['score']),
                    'type' => $qa['type'],
                    'is_required' => 1
                ]);

                $question->answerOptions()->create([
                    'question_id' => $question->id,
                    'option' => $qa['answer'],
                ]);
            }
        }


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

    public function deleteQuestion($question)
    {
        Question::destroy($question);

        return response()->json(['message' => 'Pertanyaan Berhasil Dihapus']);
    }

    public function import(Request $request)
    {
        $validator = $request->validate([
            'file' => 'file|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file');
        // upload ke folder file_siswa di dalam folder public
        $validator['file'] = $file->store('files');
        Excel::import(new QnAImport, request()->file('file'));

        return redirect()->back()->with('success', 'Data Berhasil di Import');
    }
}
