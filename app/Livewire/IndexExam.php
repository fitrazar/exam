<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Answer;
use App\Models\Result;
use App\Models\Student;
use Livewire\Component;
use App\Models\Question;
use Illuminate\Support\Str;
use App\Models\AnswerOption;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class IndexExam extends Component
{
    use LivewireAlert;

    public $exam, $count;
    public $currentStep = 0;
    #[Validate([
        'answers' => 'required',
        'answers.*' => 'required',
    ], message: [
        'required' => 'Pastikan Jawaban Terisi Semua!',
        'answers.required' => 'Pastikan Jawaban Terisi Semua!',
    ], attribute: [
        'answers.*' => 'Jawaban',
    ])]
    public $answers = [];
    public function mount(Exam $exam)
    {
        $this->exam = $exam;
        $this->count = $exam->questions()->count();

    }

    public function render()
    {

        $time_start = Carbon::createFromFormat('H:i:s', $this->exam->time_start);
        $time_end = Carbon::createFromFormat('H:i:s', $this->exam->time_end);

        $diffInHours = $time_start->diffInHours($time_end);

        $diffInMinutes = $time_start->diffInMinutes($time_end);


        return view('livewire.index-exam', compact('diffInMinutes'))->extends('layouts.app')->section('content');
    }

    public function back($step)
    {
        $this->currentStep = $step;
    }

    public function zeroStepSubmit()
    {
        $this->currentStep = 1;
    }

    public function firstStepSubmit()
    {
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        $this->currentStep = 3;
    }


    public function submitForm()
    {
        // dd($this->answers);
        $this->validate();
        if (count($this->answers) < $this->count) {
            return $this->alert('error', 'Jawaban Belum Terisi Semua!', [
                'position' => 'top-end',
                'timer' => '5000',
                'toast' => true,
                'timerProgressBar' => true,
            ]);
        }
        $keys = array_keys($this->answers);
        // $answer = AnswerOption::whereIn('question_id', $keys)->get();


        $score = 0;
        // dd($this->answers);
        foreach ($this->answers as $questionId => $userAnswer) {
            $status = '';
            if (!is_array($userAnswer)) {
                $correctAnswer = AnswerOption::whereIn('question_id', $keys)->whereHas('question', function ($query) {
                    $query->where('type', 0);
                })->pluck('option');
                foreach ($correctAnswer as $ca) {
                    if ($userAnswer === $ca) {
                        $status = "Benar";
                        $score += 100 / count($this->answers);
                    } else {
                        $status = "Salah";
                    }
                }

                $correctAnswerEssay = AnswerOption::whereIn('question_id', $keys)->whereHas('question', function ($query) {
                    $query->where('type', 2);
                })->pluck('option');
                if (Str::of($userAnswer)->length() > 1) {
                    foreach ($correctAnswerEssay as $cae) {
                        $levenshteinDistance = levenshtein($cae, $userAnswer);
                        $thresholdKurang = 20;
                        $thresholdBenar = 15;
                        if ($levenshteinDistance <= $thresholdKurang && $levenshteinDistance >= $thresholdBenar) {
                            $status = "Kurang";
                            $score += 50 / count($this->answers);
                        } else if ($levenshteinDistance <= $thresholdBenar) {
                            $status = "Benar";
                            $score += 100 / count($this->answers);
                        } else {
                            // dd($levenshteinDistance);
                            $status = "Salah";
                        }
                    }
                }
            } elseif (is_array($userAnswer)) {
                $correctOptions = AnswerOption::whereIn('question_id', $keys)->whereHas('question', function ($query) {
                    $query->where('type', 1);
                })->pluck('option');
                foreach ($correctOptions as $co) {
                    $co = explode('|', $co);
                    sort($co);

                    $userOptions = array_keys(array_filter($userAnswer));
                    sort($userOptions);
                    $userAnswer = implode('|', $userOptions);
                    if ($userOptions === $co) {
                        $status = "Benar";
                        $score += 100 / count($this->answers);
                    } else {
                        $status = "Salah";
                    }
                }
            }

            Answer::create([
                'question_id' => $questionId,
                'student_id' => auth()->user()->id,
                'answer' => $userAnswer,
                'status' => $status,
            ]);
        }
        $score = min(100, $score);

        $current_time = now();
        $time_end = Carbon::createFromFormat('H:i:s', $this->exam->time_end);
        $is_late = '';

        if ($current_time > $time_end) {
            $is_late = 'Terlambat ' . $current_time->diffInMinutes($time_end) . ' Menit';
            $late_duration = $current_time->diffInMinutes($time_end);
            $penalty_points = $late_duration * 2;
            $student = Student::find(auth()->user()->id);
            $student->point -= $penalty_points;
            $student->save();
        } else {
            $is_late = 'Selesai';
        }
        Result::create([
            'exam_id' => $this->exam->id,
            'student_id' => auth()->user()->id,
            'total_score' => $score,
            'status' => $is_late,
        ]);


        // dd($this->first_name);
        $this->reset('answers');
        session()->flash('success', 'Kamu berhasil mengikuti ujian.');
        $this->redirect('/');
    }

}
