<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'category_id', 
    'complainant_name', 
    'complainant_phone', 
    'complainant_email', 
    'subject', 
    'description', 
    'priority', 
    'status'
])]
class Complaint extends Model
{
    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function responses()
    {
        return $this->hasMany(ComplaintResponse::class)->orderBy('created_at', 'desc');
    }
}
