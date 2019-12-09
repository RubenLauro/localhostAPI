<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlaceType extends Model
{
    //

    protected $table = "place_types";


    protected $fillable = [
        'type_id', 'place_id'
    ];

}
