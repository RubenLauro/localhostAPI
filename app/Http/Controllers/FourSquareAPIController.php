<?php

namespace App\Http\Controllers;

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
            $apicid . '&client_secret=' . $apicsecret . '&v=20191129&limit=10&near=leiria&query=' . $name);
        return $result;
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
