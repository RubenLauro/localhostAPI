<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class ZomatoAPIController extends Controller
{
    private $apikey;
    private $apiid /*= env("YELP_API_ID")*/;

    public static function searchByName($name)
    {
        $apikey = env("ZOMATO_API_KEY");
        $client = new Client();
        $result = $client->get('https://developers.zomato.com/api/v2.1/search?q=' . $name, [
            'headers' => ['user-key' => $apikey]
        ]);
        $body = json_decode($result->getBody());
        return $body->restaurants;
    }

    /**
     * Test api
     */
    public function test(Request $request){
        $apikey = env("ZOMATO_API_KEY");
        $client = new Client();
        $result = $client->get('https://developers.zomato.com/api/v2.1/search?q=lisbon&count=10', [
            'headers' => ['user-key' => $apikey]
        ]);
        $body = json_decode($result->getBody());
        return $body->restaurants;
    }

    /**
     * Gets reviews from id of business
     *
     * @param int|string $id
     *
     * @return array
     */
    public static function get_reviews($id)
    {
        $apikey = env("ZOMATO_API_KEY");
        $client = new Client();
        $result = $client->get('https://developers.zomato.com/api/v2.1/reviews?res_id=' . $id, [
            'headers' => ['user-key' => $apikey]
        ]);
        $reviews = json_decode($result->getBody())->reviews;
        foreach ($reviews as $review){
            $r = Review::where('user', $review->user->name)->where('text',$review->review_text)->where('provider',"zomato");
            if(!$r){
                $r = new Review();
                $r->user = $review->user->name;
                $r->text = $review->review_text;
                $r->provider = "zomato";
                $r->score= $review->rating;
                $r->save();
            }
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
