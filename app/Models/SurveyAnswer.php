<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['response_id', 'question_id', 'answer_value'])]
class SurveyAnswer extends Model
{
    public function response()
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }
}
