<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Answer;
use App\Models\Result;
use App\Models\Student;
use Livewire\Component;
use App\Models\Question;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\AnswerOption;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class IndexExam extends Component
{
    use LivewireAlert;

    public $exam, $count, $penalty = 0;
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

    #[On('hayo')]
    public function ketauan()
    {
        $this->penalty += 1;
        $this->alert('info', 'Hayo abis nyontek ya', [
            'position' => 'center',
            'timer' => '',
            'toast' => false,
            'showConfirmButton' => true,
            'onConfirmed' => '',
            'confirmButtonText' => 'IYA',
        ]);
    }

    public function fullscreen()
    {
        $this->dispatch('enterFullscreen');
    }

    public function zeroStepSubmit()
    {
        $this->currentStep = 1;
        $this->dispatch('enterFullscreen');
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
        // dd($this->penalty);
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
        ksort($this->answers);

        foreach ($this->answers as $questionId => $userAnswer) {
            if (!is_array($userAnswer)) {
                if (Str::of($userAnswer)->length() <= 1) {
                    $correctAnswer = AnswerOption::where('question_id', $questionId)->whereHas('question', function ($query) {
                        $query->where('type', 0);
                    })->where('option', $userAnswer)->first();
                    // sort($correctAnswer);
                    // dd($correctAnswer);
                    if ($correctAnswer) {
                        // dd('benar');
                        $status = "Benar";
                        $score += 100 / count($this->answers);
                    } else {
                        // dd('salah');
                        $status = "Salah";
                    }


                } else {
                    $correctAnswerEssay = AnswerOption::where('question_id', $questionId)->whereHas('question', function ($query) {
                        $query->where('type', 2);
                    })->first();

                    if ($correctAnswerEssay) {
                        $levenshteinDistance = levenshtein($correctAnswerEssay->option, $userAnswer);
                        if (Str::of($correctAnswerEssay->option)->length() <= 55) {
                            $thresholdKurang = 20;
                            $thresholdBenar = 15;
                        } else {
                            $thresholdKurang = 200;
                            $thresholdBenar = 160;
                        }
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
                    } else {
                        // dd('salah');
                        $status = "Salah";
                    }

                }
            } elseif (is_array($userAnswer)) {
                $userOptions = array_keys(array_filter($userAnswer));
                sort($userOptions);
                $userAnswer = implode('|', $userOptions);

                $correctOptions = AnswerOption::where('question_id', $questionId)->whereHas('question', function ($query) {
                    $query->where('type', 1);
                })->where('option', $userAnswer)->first();
                if ($correctOptions) {

                    $ao = explode('|', $userAnswer);
                    sort($ao);
                    $co = explode('|', $correctOptions->option);
                    sort($co);
                    // dd($co);

                    $userAnswer = implode('|', $ao);
                    if ($ao == $co) {
                        $status = "Benar";
                        $score += 100 / count($this->answers);
                    } else {
                        $status = "Salah";
                    }
                } else {
                    // dd('salah');
                    $status = "Salah";
                }

            }

            // dd($status);
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
        $student = Student::find(auth()->user()->id);
        if ($this->penalty > 0) {
            $student->point -= $this->penalty;
            $student->save();
        }

        if ($current_time > $time_end) {
            $is_late = 'Terlambat ' . $current_time->diffInMinutes($time_end) . ' Menit';
            $late_duration = $current_time->diffInMinutes($time_end);
            $penalty_points = $late_duration * 2;

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
