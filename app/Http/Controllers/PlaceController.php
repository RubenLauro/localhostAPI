<?php

namespace App\Http\Controllers;

use App\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaceController extends Controller
{
    public function storeFavorite(Place $place){
        if($place->is_favorite(Auth::id())->isEmpty())
            Auth::user()->favorites()->attach($place->id);
        else
            return response()->json(["message" => "Place already a favorite!"], 409);
        return response()->json(["message" => "Favorite added successfully!"], 200);
    }

    public function deleteFavorite(Place $place){
        if(!$place->is_favorite(Auth::id())->isEmpty())
            Auth::user()->favorites()->detach($place->id);
        else
            return response()->json(["message" => "Place does not exist in the favorites list!"], 409);
        return response()->json(["message" => "Favorite removed successfully!"], 200);
    }

    public function hasQuestion(Place $place){
        foreach (Auth::user()->questions as $question) {
            if ($place->id == $question->place->id){
                return response()->json(true, 200);
            }
        }

        return response()->json(false, 404);
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
