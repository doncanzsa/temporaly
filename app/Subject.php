<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];

    public function lessons()
    {
        return $this->hasMany('App\lesson');
    }
}
