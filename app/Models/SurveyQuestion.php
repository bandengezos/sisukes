<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['survey_id', 'question_text', 'type', 'options', 'sort_order'])]
class SurveyQuestion extends Model
{
    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }
}
