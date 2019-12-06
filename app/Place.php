<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'image_url',
        'address',
        'average_rating',
        'reviews',
        'latitude',
        'longitude',
        'types',
        'city',
        'qt_reviews',
        'provider',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function place_types(){
        return $this->belongsToMany('App\Type','place_types','type_id','place_id');
    }

    public function reviews(){
        return $this->hasMany('App\Review','place_id','id');
    }

    /**
     * Compares both latitude and longitude to the precision of 4 decimals
     * with a range of 0.0002 units upwards and downwards as margin of error
     *
     * @param $coordinates1 array({'latitude': float, 'longitude': float})
     * @param $coordinates2 array({'latitude': float, 'longitude': float})
     *
     * @return Boolean <strong>true</strong> if it's the same place, <strong>false</strong> if not
     */
    private function is_same_place($coordinates1, $coordinates2)
    {
        //check latitude
        $is_latitude_equal = false;
        $is_longitude_equal = false;

        if (abs($coordinates1[0]['latitude'] - $coordinates2[0]['latitude']) < 0.0002) {
            $is_latitude_equal = true;
        }
        if (abs($coordinates1[0]['longitude'] - $coordinates2[0]['longitude']) < 0.0002) {
            $is_longitude_equal = true;
        }
        return $is_latitude_equal && $is_longitude_equal;
    }

}
