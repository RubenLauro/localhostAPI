<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FoursquarePOI extends Model
{

    /**
     * Object({
     *      id: string,
     *      name: string,
     *      location: string,
     *      cc: string,
     *      formatted_address: string,
     *      categories: Array({id,name,pluralName,shortName,icon:Array(prefix),primary})
     * })
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'location',
        'cc',
        'formatted_address',
        'categories'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
