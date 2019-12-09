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
        'city',
        'average_rating',
        'latitude',
        'longitude',
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

    protected $appends = ['types'];

    public function place_types(){
        return $this->belongsToMany('App\Type','place_types','place_id','type_id');
    }

    public function reviews(){
        return $this->hasMany('App\Review','place_id','id');
    }

    public function getTypesAttribute(){
        $types = array();
        foreach ($this->place_types()->get() as $type) {
            array_push($types, $type->name);
        }
        return $this->attributes['types'] = $types;
    }

    public function setQtReviewsAttribute(){
        return $this->attributes['qt_reviews'] = $this->reviews()->get()->count();
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

    public function getRadius($curLat, $curLong){
        return $this->haversineGreatCircleDistance($curLat, $curLong,
            $this->latitude, $this->longitude);
    }

    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

}
