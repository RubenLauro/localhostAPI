<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Http\Resources\PlaceResource;
use App\Place;
use App\Region;
use http\Client;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class LocalhostAPIController extends Controller
{

    /**
     * Search by name
     */
    function searchByName(Request $request)
    {
        $name = $request->input('name');
        $places = new Collection();
        /**
         * Yelp
         *
         * response.businesses
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
        $yelpResults = json_decode(YelpAPIController::searchByName($name));

        /**
         * Zomato
         *
         * response.restaurants
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
        $zomatoResults = ZomatoAPIController::searchByName($name);

        /**
         * Foursquare
         *
         * response.venues
         * Object({
         *      id: string,
         *      name: string,
         *      location: string,
         *      cc: string,
         *      formatted_address: string,
         *      categories: Array({id,name,pluralName,shortName,icon:Array(prefix),primary})
         * })
         */
        $foursquareResults = FourSquareAPIController::searchByName($name);

        //Go through YELP results first
        foreach ($yelpResults->businesses as $yelpResult) {
            $current_result = $yelpResult;
            $place = new Place();
            $place->name = $current_result->name;
            $place->address = $current_result->location->display_address[0];
            $place->average_rating = $current_result->rating;
            $place->latitude = $current_result->coordinates->latitude;
            $place->longitude = $current_result->coordinates->longitude;
            $place->city = mb_strtolower($current_result->location->city);
            //$place->city = $this->find_city($current_result->location->city);
            $place->types = $this->parse_categories_array($current_result->categories, "yelp");
            //$this->find_categories(
            //                $current_result->categories,
            //                "yelp");

            $place->reviews = $this->get_reviews($current_result->id, "yelp");
            $place->qt_reviews = $current_result->review_count;
            //dd($place);
            //dd($place);
            $places = $places->push($place);
            Log::warning($place);
            //array_push($places, $place);
        }
        //There are 10 places
        // now we have to merge information from the 10 places
        // gotten from other APIS
       // dd($places);
        return $places;
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


    /**
     * Searches for city name in lower case and creates it if it doesn't exist
     *
     * @param $city string city name
     *
     * @return Region found or newly created region Id
     */
    private function find_city($city)
    {
        $city_to_use = City::where('name', strtolower($city))->get();

        if ($city_to_use->isEmpty()) {
            $city_to_use = new City();
            $city_to_use->name = strtolower($city);
            $city_to_use->description = $city;
            $city_to_use->save();

        }
        return $city_to_use->first()->id;
    }

    /**
     * Searches for category name in lower case and creates it if it doesn't exist
     *
     * @param $categories array|string category name(s) to find
     * @param $provider string provider name. Supported: "yelp", "zomato", "fsquare"
     *
     * @return array found or newly created category(ies) Id(s)
     * @throws \InvalidArgumentException Invalid argument categories
     */
    private function find_categories($categories, $provider)
    {
        if (!contains(array("yelp", "foursquare", "zomato"), $provider)) {
            throw new \InvalidArgumentException("Provider value is not supported: ", $provider);
        }
        $array_ids = array();
        if ($provider == "yelp") {
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    $c = Category::where('name', strtolower($category->alias))->get();
                    if ($c->isEmpty()) {
                        $c = new Category();
                        $c->name = strtolower($category->alias);
                        $c->save();
                    }
                    array_push($array_ids, $c->first()->id);
                }
                return $array_ids;
            } else {
                throw new InvalidArgumentException("Expected Array but got " . gettype($categories));
            }
        } else if ($provider = "fsquare") {
            if (is_array($categories)) {
                foreach ($categories as $category) {
                    $c = Category::where('name', strtolower($category->shortName))->get();
                    if ($c->isEmpty()) {
                        $c = new Category();
                        $c->name = strtolower($category->shortName);
                        $c->save();
                    }
                    array_push($array_ids, $c->first()->id);
                }
            } else {
                throw new InvalidArgumentException("Expected Array but got " . gettype($categories));
            }
        } else if ($provider == "zomato") { // if not array then it's Zomato data (comma separated strings)
            if (is_string($categories)) {
                $categories_parts = explode(',', $categories);
                foreach ($categories_parts as $category) {
                    $c = Category::where('name', strtolower($category))->get();
                    if ($c->isEmpty()) {
                        $c = new Category();
                        $c->name = strtolower($category);
                        $c->save();
                    }
                    array_push($array_ids, $c->first()->id);
                }
            } else {
                throw new InvalidArgumentException("Expected String but got " . gettype($categories));
            }
        }
        return null;
    }

    /**
     * Parses categories object from given provider
     *
     * @param $categories array Array of category results
     * @param $provider string Provider. Supports "yelp", "fsquare", "zomato"
     *
     * @return array of categories
     */
    private function parse_categories_array($categories, $provider)
    {
        $result = array();
        if($provider == "yelp"){
            foreach ($categories as $category){
                array_push($result, strtolower($category->alias));
            }
            return $result;
        }else if($provider == "fsquare"){
            foreach ($categories as $category){
                array_push($result, strtolower($category->shortName));
            }
            return $result;
        }else if($provider == "zomato"){

        }
    }

    /**
     * @param string|integer $id Id of object returned from api
     * @param string $provider Provider. Supports "yelp", "fsquare", "zomato"
     */
    private function get_reviews($id, string $provider)
    {
        if($provider == "yelp"){
            $result = YelpAPIController::get_reviews($id);
            return $result;
        }
    }
}
