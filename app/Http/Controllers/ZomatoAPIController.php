<?php

namespace App\Http\Controllers;

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