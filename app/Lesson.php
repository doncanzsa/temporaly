<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $guarded = [];

    public function subjects()
    {
        return $this->belongsTo('App\Subject', 'subject_id');
    }
}
