<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YelpPOI extends Model
{
    /**
     * Object({
     *      id: string,
     *      alias: string,
     *      name: string,
     *      image_url: string,
     *      is_closed: boolean,
     *      url: string,
     *      categories: Array({alias: string, name: string}),
     *      rating: float,
     *      coordinates: Array({latitude: float, longitude:float}),
     *      location: Array({address1/2/3, city, zip-code,country,display_address})
     * })
     */


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'alias',
        'name',
        'image_url',
        'is_closed',
        'url',
        'categories',
        'rating',
        'coordinates',
        'location'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
