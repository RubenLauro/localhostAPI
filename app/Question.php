<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;
    //
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function place()
    {
        return $this->belongsTo('App\Place');
    }
}
