<?php

namespace App\Livewire;

use App\Models\Exam;
use Livewire\Component;

class IndexStudent extends Component
{
    public function render()
    {
        $exams = Exam::whereDate('date_start', now())->where('grade_id', auth()->user()->grade_id)
            ->where('major_id', auth()->user()->major_id)->where('group_id', auth()->user()->group_id)
            ->get();
        return view('livewire.index-student', [
            'exams' => $exams
        ])->extends('layouts.app')->section('content');
    }


}
