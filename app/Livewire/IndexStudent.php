<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\Result;
use Livewire\Component;

class IndexStudent extends Component
{
    public function render()
    {
        $exams = Exam::whereDate('date_start', now())->where('grade_id', auth()->user()->grade_id)
            ->where('major_id', auth()->user()->major_id)->where('group_id', auth()->user()->group_id)
            ->get();

        foreach ($exams as $exam) {
            $currentTime = now();
            $timeStart = \Carbon\Carbon::createFromFormat('H:i:s', $exam->time_start);
            $timeEnd = \Carbon\Carbon::createFromFormat('H:i:s', $exam->time_end);

            $isActive = $currentTime->lte($timeStart) || $currentTime->gte($timeEnd);

            $result = Result::where('exam_id', $exam->id)
                ->where('student_id', auth()->user()->id)
                ->first();

            $exam->status = $result ? $result->status : ($isActive ? 'Tidak Aktif' : 'Aktif');
            $exam->joinStatus = $result ? '-' : ($isActive ? '-' : '<a href="' . url('/exam/' . $exam->code) . '" wire:navigate>Ikut</a>');
        }
        return view('livewire.index-student', [
            'exams' => $exams
        ])->extends('layouts.app')->section('content');
    }


}
