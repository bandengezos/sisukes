<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'slug'])]
class ComplaintCategory extends Model
{
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'category_id');
    }
}
