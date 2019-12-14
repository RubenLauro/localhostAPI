<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class YelpAPIController extends Controller
{

    private $apikey;
    private $apiid;


    /**
     * Gets reviews from id of business
     *
     * @param int|string $id
     *
     * @return array
     */
    public static function get_reviews($id)
    {
        $apikey = env("YELP_API_KEY");
        $client = new Client();
        $result = $client->get('https://api.yelp.com/v3/businesses/' . $id . '/reviews', [
            'headers' => ['Authorization' => 'Bearer ' . $apikey]
        ]);
        $reviews = json_decode($result->getBody())->reviews;
        foreach ($reviews as $review){
            $r = Review::where('user', $review->user->name)->where('text',$review->text)->where('provider',"yelp");
            if(!$r){
                $r = new Review();
                $r->user = $review->user->name;
                $r->text = $review->text;
                $r->provider = "yelp";
                $r->score= $review->rating;
                $r->save();
            }
        }
    }

    public static function searchByRadius($curLat, $curLon, $radius)
    {
        $apikey = env("YELP_API_KEY");
        $client = new Client();
        $result = $client->get('https://api.yelp.com/v3/businesses/search?sort_by=distance&radius=' . $radius .'&latitude=' . $curLat . '&longitude=' . $curLon, [
            'headers' => ['Authorization' => 'Bearer ' . $apikey]
        ]);
        return $result->getBody();
    }

    public static function searchByRanking($curLat, $curLon, $radius, $ranking)
    {
        $apikey = env("YELP_API_KEY");
        $client = new Client();
        $result = $client->get('https://api.yelp.com/v3/businesses/search?sort_by=distance&radius=' . $radius . '&latitude=' . $curLat . '&longitude=' . $curLon . '&rating=' . $ranking, [
            'headers' => ['Authorization' => 'Bearer ' . $apikey]
        ]);
        return $result->getBody();
    }

    /**
     * Test api
     */
    public function test(Request $request)
    {
//        if($request->filled('location')){
        $apikey = env("YELP_API_KEY");
        $client = new Client();
        $result = $client->get('https://api.yelp.com/v3/businesses/search?location=leiria', [
            'headers' => ['Authorization' => 'Bearer ' . $apikey]
        ]);
//        }
        return $result;
    }

    public static function searchByName($query)
    {
        $apikey = env("YELP_API_KEY");
        $client = new Client();
        $result = $client->get('https://api.yelp.com/v3/businesses/search?location=leiria&term=' . $query, [
            'headers' => ['Authorization' => 'Bearer ' . $apikey]
        ]);
        return $result->getBody();
    }

    public static function searchByCity($query)
    {
        $apikey = env("YELP_API_KEY");
        $client = new Client();
        $result = $client->get('https://api.yelp.com/v3/businesses/search?location=' . $query, [
            'headers' => ['Authorization' => 'Bearer ' . $apikey]
        ]);
        return $result->getBody();
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
