<?php

namespace App\Http\Controllers;

use App\Review;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FourSquareAPIController extends Controller
{
    private $apikey;
    private $apicsecret;

    public static function searchByName($name)
    {
        $apicid = env('FOURSQUARE_API_CLIENT_ID');
        $apicsecret = env('FOURSQUARE_API_CLIENT_SECRET');
        $client = new Client();
        $result = $client->get('https://api.foursquare.com/v2/venues/search?client_id=' .
            $apicid . '&client_secret=' . $apicsecret . '&v=20191129&limit=20&categoryID=4d4b7105d754a06374d81259&near=leiria&query=' . $name);
        return $result;
    }

    public static function searchByRadius($curLat, $curLon, $radius)
    {
        $apicid = env('FOURSQUARE_API_CLIENT_ID');
        $apicsecret = env('FOURSQUARE_API_CLIENT_SECRET');
        $client = new Client();
        $result = $client->get('https://api.foursquare.com/v2/venues/search?client_id=' .
            $apicid . '&client_secret=' . $apicsecret . "&ll={$curLat},{$curLon}&v=20191129&limit=20&categoryId=4d4b7105d754a06374d81259&radius={$radius}");

        return $result->getBody();
    }

    public static function searchByLocal($local)
    {
        $apicid = env('FOURSQUARE_API_CLIENT_ID');
        $apicsecret = env('FOURSQUARE_API_CLIENT_SECRET');
        $client = new Client();
        $result = $client->get('https://api.foursquare.com/v2/venues/search?client_id=' .
            $apicid . '&client_secret=' . $apicsecret . "&near={$local}&v=20191129&limit=20&categoryId=4d4b7105d754a06374d81259");

        return $result->getBody();
    }
    /**
     * Gets reviews from id of business
     *
     * @param $id string to update reviews
     * @param $place_id int to update reviews
     *
     */
    public static function get_reviews($id, $place_id)
    {
        $apicid = env('FOURSQUARE_API_CLIENT_ID');
        $apicsecret = env('FOURSQUARE_API_CLIENT_SECRET');
        $client = new Client();
        $result = $client->get('https://api.foursquare.com/v2/venues/' . $id . '/tips?&v=20191129&client_id=' .
            $apicid . '&client_secret=' . $apicsecret);
        if (isset(json_decode($result->getBody())->response->tips)) {
            $reviews = json_decode($result->getBody())->response->tips->items;
            foreach ($reviews as $review) {
                $r = Review::where('user_name', $review->user->firstName)->where('text', $review->text)->where('provider', "fsquare")->get();
                if ($r->isEmpty()) {
                    $r = new Review();
                    $r->user_name = $review->user->firstName ?? '';
                    if (isset($review->user->photo))
                        $r->user_image = $review->user->photo->prefix.'400x225'.$review->user->photo->suffix;
                    else
                        $r->user_image = '';
                    $r->text = $review->text ?? '';
                    $r->provider = "fsquare";
                    $r->rating = 3;
                    $r->place_id = $place_id ?? -1;
                    $r->save();
                }
            }
        }
    }

    public static function getDetails($id)
    {
        $apicid = env('FOURSQUARE_API_CLIENT_ID');
        $apicsecret = env('FOURSQUARE_API_CLIENT_SECRET');
        $client = new Client();
        $result = $client->get("https://api.foursquare.com/v2/venues/{$id}?client_id=" .
            $apicid . '&client_secret=' . $apicsecret . '&v=20191129');
        return $result->getBody();
    }

    public function test()
    {
        $apicid = env('FOURSQUARE_API_CLIENT_ID');
        $apicsecret = env('FOURSQUARE_API_CLIENT_SECRET');
        $client = new Client();
        $result = $client->get('https://api.foursquare.com/v2/venues/search?client_id=' .
            $apicid . '&client_secret=' . $apicsecret . '&v=20191129&limit=20&near=leiria&query=casarao');
        return $result;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
