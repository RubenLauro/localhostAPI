<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZomatoPOI extends Model
{

    /**
     * Object({
     *      R: has_menu_status({delivery: number | -1, takeaway: number | -1}),
     *      id: number,
     *      name: string,
     *      url: string,
     *      location: Array({
     *           address:string,
     *           locality: string,
     *           city: string,
     *           city_id: number,
     *           latitude: float,
     *           longitude: float,
     *           zipcode: string,
     *           country_id: number,
     *           locality_verbose: string
     *      ]),
     *      cuisines: string ("XX, XX, XX"),
     *      average_cost_for_two: number,
     *      price_range: number,
     *      currency: string ("€"),
     *      highlights: Array({string, string, ...}),
     *      all_reviews_count: number,
     *      user_rating: Array({
     *           aggregate_rating: float,
     *           rating_text: string,
     *           rating_color: string,
     *           rating_obj: Array({title:Array(), bg_color:Array()})
     *           votes: number
     *      }),
     *      photo_count: number,
     *      photos_url: string,
     *      phone_numbers: string ("XX, XX, XX"),
     *      establishment: string
     *
     * })
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}
