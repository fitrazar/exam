<?php

namespace App\Imports;

use App\Models\Exam;
use App\Models\Question;
use App\Models\AnswerOption;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QnAImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $kodeUjian = preg_replace('/\s+/', '', $row['kode_ujian']);

        $id_exam = Exam::where('code', $kodeUjian)->first()->id;

        if (!empty($row['pg_jika_tipe_nya_pg_pg_kompleks'])) {
            $pg = str_replace('|', "\n", $row['pg_jika_tipe_nya_pg_pg_kompleks']);
            $questionText = $row['pertanyaan'] . "\n" . $pg;
        } else {
            $questionText = $row['pertanyaan'];
        }

        $question = Question::create([
            'exam_id' => $id_exam,
            'type' => $row['tipe'],
            'question' => $questionText,
            'score' => 0,
            'is_required' => 1,
        ]);

        $totalQuestions = Question::where('exam_id', $id_exam)->count();

        $maxScorePerQuestion = ($totalQuestions > 0) ? 100 / $totalQuestions : 0;

        $questions = Question::where('exam_id', $id_exam)->get();

        foreach ($questions as $question) {
            $question->update([
                'score' => intval($maxScorePerQuestion),
            ]);
        }

        $question->answerOptions()->create([
            'question_id' => $question->id,
            'option' => $row['jawaban'],
        ]);

        return $question;
    }



    public function headingRow(): int
    {
        return 1;
    }
}
