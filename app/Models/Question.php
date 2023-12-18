<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['exam'];

    /**
     * Get the exam that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    /**
     * Get all of the answer for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    /**
     * Get all of the answer options for the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answerOptions(): HasMany
    {
        return $this->hasMany(AnswerOption::class, 'question_id');
    }
}
