<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Question;
use Illuminate\Support\Str;
use App\Models\AnswerOption;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class IndexExam extends Component
{
    use LivewireAlert;

    public $exam, $count;
    public $currentStep = 1;
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
        return view('livewire.index-exam')->extends('layouts.app')->section('content');
    }

    public function back($step)
    {
        $this->currentStep = $step;
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

        foreach ($this->answers as $userAnswer) {
            if (!is_array($userAnswer)) {

                $correctAnswer = AnswerOption::whereIn('question_id', $keys)->whereHas('question', function ($query) {
                    $query->where('type', 0);
                })->pluck('option');
                foreach ($correctAnswer as $ca) {
                    if ($userAnswer === $ca) {
                        $score += 100 / count($this->answers);
                    }
                }

                $correctAnswerEssay = AnswerOption::whereIn('question_id', $keys)->whereHas('question', function ($query) {
                    $query->where('type', 2);
                })->pluck('option');
                if (Str::of($userAnswer)->length() > 1) {
                    foreach ($correctAnswerEssay as $cae) {
                        $levenshteinDistance = levenshtein($cae, $userAnswer);
                        $threshold = 20;
                        if ($levenshteinDistance <= $threshold) {
                            $score += 100 / count($this->answers);
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

                    if ($userOptions === $co) {
                        $score += 100 / count($this->answers);
                    }
                }
            }
        }
        $score = min(100, $score);
        dd($score);


        // dd($this->first_name);
        $this->reset('answers');
    }

}
