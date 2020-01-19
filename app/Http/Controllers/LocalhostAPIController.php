<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Http\Resources\PlaceResource;
use App\Place;
use App\PlaceType;
use App\Region;
use App\Type;
use http\Client;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LocalhostAPIController extends Controller
{
    /**
     * Runs daily
     */
    public static function fetchPlaces()
    {
        $concelhos = [
            'Alcácer do Sal Portugal',
            'Castelo Branco Portugal',
            'Idanha-a-Nova Portugal',
            'Évora Portugal',
            'Mértola Portugal',
            'Montemor-o-Novo Portugal',
            'Bragança Portugal',
            'Beja Portugal',
            'Coruche Portugal',
            'Serpa Portugal',
            'Santiago do Cacém Portugal',
            'Moura Portugal',
            'Ponte de Sor Portugal',
            'Sabugal Portugal',
            'Grândola Portugal',
            'Montalegre Portugal',
            'Almodôvar Portugal',
            'Loulé Portugal',
            'Mogadouro Portugal',
            'Chamusca Portugal',
            'Abrantes Portugal',
            'Guarda Portugal',
            'Fundão Portugal',
            'Macedo de Cavaleiros Portugal',
            'Vinhais Portugal',
            'Arraiolos Portugal',
            'Silves Portugal',
            'Ourique Portugal',
            'Mirandela Portugal',
            'Ferreira do Alentejo Portugal',
            'Elvas Portugal',
            'Pombal Portugal',
            'Tavira Portugal',
            'Avis Portugal',
            'Portel Portugal',
            'Chaves Portugal',
            'Nisa Portugal',
            'Alcoutim Portugal',
            'Castro Verde Portugal',
            'Leiria Portugal',
            'Penamacor Portugal',
            'Santarém Portugal',
            'Covilhã Portugal',
            'Valpaços Portugal',
            'Alandroal Portugal',
            'Torre de Moncorvo Portugal',
            'Benavente Portugal',
            'Almeida Portugal',
            'Estremoz Portugal',
            'Figueira de Castelo Rodrigo Portugal',
            'Viseu Portugal',
            'Miranda do Douro Portugal',
            'Pinhel Portugal',
            'Vimioso Portugal',
            'Oleiros Portugal',
            'Reguengos de Monsaraz Portugal',
            'Palmela Portugal',
            'Aljustrel Portugal',
            'Arcos de Valdevez Portugal',
            'Portalegre Portugal',
            'Sertã Portugal',
            'Mora Portugal',
            'Vila Pouca de Aguiar Portugal',
            'Seia Portugal',
            'Monforte Portugal',
            'Ourém Portugal',
            'Alcobaça Portugal',
            'Torres Vedras Portugal',
            'Mação Portugal',
            'Vila Nova de Foz Côa Portugal',
            'Crato Portugal',
            'Pampilhosa da Serra Portugal',
            'Monchique Portugal',
            'Proença-a-Nova Portugal',
            'Viana do Alentejo Portugal',
            'Cantanhede Portugal',
            'Castro Daire Portugal',
            'Figueira da Foz Portugal',
            'Barcelos Portugal',
            'Vila Real Portugal',
            'Tondela Portugal',
            'Redondo Portugal',
            'Alter do Chão Portugal',
            'Trancoso Portugal',
            'Tomar Portugal',
            'São Pedro do Sul Portugal',
            'Montijo Portugal',
            'Águeda Portugal',
            'Arganil Portugal',
            'Vila Velha de Ródão Portugal',
            'Arouca Portugal',
            'Aljezur Portugal',
            'Boticas Portugal',
            'Alfândega da Fé Portugal',
            'Ponte de Lima Portugal',
            'Coimbra Portugal',
            'Sintra Portugal',
            'Viana do Castelo Portugal',
            'Vila Franca de Xira Portugal',
            'Vidigueira Portugal',
            'Arronches Portugal',
            'Alenquer Portugal',
            'Amarante Portugal',
            'Castro Marim Portugal',
            'Gouveia Portugal',
            'Alijó Portugal',
            'Gavião Portugal',
            'Mafra Portugal',
            'Mêda Portugal',
            'Sousel Portugal',
            'Carrazeda de Ansiães Portugal',
            'Mourão Portugal',
            'Terras de Bouro Portugal',
            'Rio Maior Portugal',
            'Torres Novas Portugal',
            'São João da Pesqueira Portugal',
            'Vila Flor Portugal',
            'Soure Portugal',
            'Castelo de Vide Portugal',
            'Alvito Portugal',
            'Azambuja Portugal',
            'Góis Portugal',
            'Porto de Mós Portugal',
            'Caldas da Rainha Portugal',
            'Mortágua Portugal',
            'Fronteira Portugal',
            'Celorico da Beira Portugal',
            'Campo Maior Portugal',
            'Freixo de Espada à Cinta Portugal',
            'Salvaterra de Magos Portugal',
            'Cabeceiras de Basto Portugal',
            'Guimarães Portugal',
            'Cinfães Portugal',
            'Angra do Heroísmo Portugal',
            'Melgaço Portugal',
            'Oliveira do Hospital Portugal',
            'Ponta Delgada Portugal',
            'Montemor-o-Velho Portugal',
            'Vila Verde Portugal',
            'Sernancelhe Portugal',
            'Vendas Novas Portugal',
            'Almeirim Portugal',
            'Moimenta da Beira Portugal',
            'Mangualde Portugal',
            'Fafe Portugal',
            'Vieira do Minho Portugal',
            'Ribeira de Pena Portugal',
            'Penacova Portugal',
            'Anadia Portugal',
            'Santa Maria da Feira Portugal',
            'Lagos Portugal',
            'Penafiel Portugal',
            'Monção Portugal',
            'Aguiar da Beira Portugal',
            'Sines Portugal',
            'Sátão Portugal',
            'Marco de Canaveses Portugal',
            'Vila Nova de Famalicão Portugal',
            'Faro Portugal',
            'Aveiro Portugal',
            'Tábua Portugal',
            'Sesimbra Portugal',
            'Vila Viçosa Portugal',
            'Vouzela Portugal',
            'Vila de Rei Portugal',
            'Ferreira do Zêzere Portugal',
            'Murça Portugal',
            'Marinha Grande Portugal',
            'Braga Portugal',
            'Ponte da Barca Portugal',
            'Portimão Portugal',
            'Celorico de Basto Portugal',
            'Ribeira Grande Portugal',
            'Vila do Bispo Portugal',
            'Ansião Portugal',
            'Vila Nova de Paiva Portugal',
            'Cadaval Portugal',
            'Baião Portugal',
            'Figueiró dos Vinhos Portugal',
            'Horta Portugal',
            'Mondim de Basto Portugal',
            'Cuba Portugal',
            'Setúbal Portugal',
            'Vagos Portugal',
            'Loures Portugal',
            'Vila Nova de Gaia Portugal',
            'Barrancos Portugal',
            'Lamego Portugal',
            'Oliveira de Azeméis Portugal',
            'Praia da Vitória Portugal',
            'Alvaiázere Portugal',
            'Cartaxo Portugal',
            'Sabrosa Portugal',
            'Paredes Portugal',
            'Albergaria-a-Velha Portugal',
            'Lajes do Pico Portugal',
            'Marvão Portugal',
            'São Brás de Alportel Portugal',
            'Vila do Conde Portugal',
            'Ovar Portugal',
            'Lourinhã Portugal',
            'Madalena Portugal',
            'Vale de Cambra Portugal',
            'Oliveira de Frades Portugal',
            'Borba Portugal',
            'São Roque do Pico Portugal',
            'Óbidos Portugal',
            'Albufeira Portugal',
            'Condeixa-a-Nova Portugal',
            'Lousã Portugal',
            'Paredes de Coura Portugal',
            'Caminha Portugal',
            'Santo Tirso Portugal',
            'Santana Portugal',
            'Penela Portugal',
            'Penalva do Castelo Portugal',
            'Tabuaço Portugal',
            'Penedono Portugal',
            'Póvoa de Lanhoso Portugal',
            'Gondomar Portugal',
            'Fornos de Algodres Portugal',
            'Olhão Portugal',
            'Sever do Vouga Portugal',
            'Pedrógão Grande Portugal',
            'Alcochete Portugal',
            'Alcanena Portugal',
            'Miranda do Corvo Portugal',
            'Calheta Portugal',
            'Nelas Portugal',
            'Mira Portugal',
            'Resende Portugal',
            'Manteigas Portugal',
            'Belmonte Portugal',
            'Velas Portugal',
            'Armamar Portugal',
            'Valença Portugal',
            'Carregal do Sal Portugal',
            'Felgueiras Portugal',
            'Castelo de Paiva Portugal',
            'Santa Comba Dão Portugal',
            'Mealhada Portugal',
            'Calheta Portugal',
            'Vila Nova de Cerveira Portugal',
            'Estarreja Portugal',
            'Povoação Portugal',
            'Batalha Portugal',
            'Tarouca Portugal',
            'Nordeste Portugal',
            'Cascais Portugal',
            'Vila do Porto Portugal',
            'Lousada Portugal',
            'Seixal Portugal',
            'Alpiarça Portugal',
            'Esposende Portugal',
            'Peso da Régua Portugal',
            'Sardoal Portugal',
            'Bombarral Portugal',
            'Lagoa Portugal',
            'Oliveira do Bairro Portugal',
            'Lisbon Portugal',
            'Vila Nova de Poiares Portugal',
            'Maia Portugal',
            'Porto Moniz Portugal',
            'Nazaré Portugal',
            'Póvoa de Varzim Portugal',
            'Amares Portugal',
            'São Vicente Portugal',
            'Constância Portugal',
            'Vila Franca do Campo Portugal',
            'Arruda dos Vinhos Portugal',
            'Peniche Portugal',
            'Golegã Portugal',
            'Funchal Portugal',
            'Valongo Portugal',
            'Ílhavo Portugal',
            'Murtosa Portugal',
            'Trofa Portugal',
            'Paços de Ferreira Portugal',
            'Santa Cruz das Flores Portugal',
            'Almada Portugal',
            'Lajes das Flores Portugal',
            'Santa Marta de Penaguião Portugal',
            'Santa Cruz Portugal',
            'Machico Portugal',
            'Castanheira de Pera Portugal',
            'Ribeira Brava Portugal',
            'Matosinhos Portugal',
            'Vila Real de Santo António Portugal',
            'Santa Cruz da Graciosa Portugal',
            'Moita Portugal',
            'Câmara de Lobos Portugal',
            'Sobral de Monte Agraço Portugal',
            'Vila Nova da Barquinha Portugal',
            'Ponta do Sol Portugal',
            'Oeiras Portugal',
            'Lagoa Portugal',
            'Porto Santo Portugal',
            'Porto Portugal',
            'Barreiro Portugal',
            'Mesão Frio Portugal',
            'Odivelas Portugal',
            'Vizela Portugal',
            'Amadora Portugal',
            'Espinho Portugal',
            'Corvo Portugal',
            'Entroncamento Portugal',
            'São João da Madeira Portugal',
        ];

        $distritos = [
            "Lisboa Portugal",
            "Porto Portugal",
            "Braga Portugal",
            "Setúbal Portugal",
            "Aveiro Portugal",
            "Faro Portugal",
            "Leiria Portugal",
            "Coimbra Portugal",
            "Santarém Portugal",
            "Viseu Portugal",
            "Madeira Portugal",
            "Açores Portugal",
            "Viana Do Castelo Portugal",
            "Vila Real Portugal",
            "Castelo Branco Portugal",
            "Évora Portugal",
            "Guarda Portugal",
            "Beja Portugal",
            "Bragança Portugal",
            "Portalegre Portugal",
        ];

        foreach ($distritos as $distrito) {
            $results = LocalhostAPIController::fetchByName($distrito);
        }

        $foursquareResults = json_decode(FourSquareAPIController::searchByLocal("leiria"));
        $controller = new LocalhostAPIController();
        foreach ($foursquareResults->response->venues as $fsResult) {
            $place = $controller->createOrUpdatePlace($fsResult,
                $fsResult->location->lat,
                $fsResult->location->lng, "fsquare");
            var_dump($place);
        }
    }

    static function fetchByName($concelho)
    {

        $yelpResults = json_decode(YelpAPIController::searchByLocation($concelho));
        $controller = new LocalhostAPIController();
        foreach ($yelpResults->businesses as $yelpResult) {
            $place = $controller->createOrUpdatePlace($yelpResult,
                $yelpResult->coordinates->latitude,
                $yelpResult->coordinates->longitude, "yelp");
            var_dump($place);
        }
        /*
        $foursquareResults = json_decode(FourSquareAPIController::searchByLocal($concelho));
        $controller = new LocalhostAPIController();
        foreach ($foursquareResults->response->venues as $fsResult) {
            $place = $controller->createOrUpdatePlace($fsResult,
                $fsResult->location->lat,
                $fsResult->location->lng, "fsquare");
            var_dump($place);
        }*/
    }

//CROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOONCROOOOOOOOOONCROOOOOOOOOON
//CROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOONCROOOOOOOOOONCROOOOOOOOOON
//CROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOON CROOOOOOOOOONCROOOOOOOOOONCROOOOOOOOOONCROOOOOOOOOON

    /**
     * Search by name
     */
    function searchByName(Request $request)
    {
        $name = $request->input('name');
        $places = new Collection();

        //go to db first
        $tmpResult = Place::where('name', 'LIKE', '%' . $name . '%')->get();
        if ($tmpResult->count() >= 1)
            return $tmpResult;

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
        //$zomatoResults = ZomatoAPIController::searchByName($name);

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
        //$foursquareResults = FourSquareAPIController::searchByName($name);

        //Go through YELP results first
        foreach ($yelpResults->businesses as $yelpResult) {
            $place = $this->createOrUpdatePlace($yelpResult,
                $yelpResult->coordinates->latitude,
                $yelpResult->coordinates->longitude, "yelp");
            $places = $places->push($place);
        }
        return $places;
    }

    /**
     * Search by radius
     */
    function searchByRadius(Request $request)
    {
        $radius = $request->input('radius');
        $curLat = $request->input('latitude');
        $curLong = $request->input('longitude');
        $places = new Collection();

        //go to db first
        $tmpResult = Place::orderBy('id', 'ASC')->get();
        $result = [];
        foreach ($tmpResult as $r) {
            if ($r->getRadius($curLat, $curLong) <= $radius) {
                array_push($result, $r);
            }
        }
        if (count($result) >= 1)
            return response()->json($result);


        $yelpResults = json_decode(YelpAPIController::searchByRadius($curLat, $curLong, $radius));
        //$zomatoResults = json_decode(ZomatoAPIController::searchByName($name);
        $foursquareResults = json_decode(FourSquareAPIController::searchByRadius($curLat, $curLong, $radius));
        //dd($foursquareResults->response->venues);
        //Go through YELP results first
/*
        foreach ($yelpResults->businesses as $yelpResult) {
            $place = $this->createOrUpdatePlace($yelpResult,
                $yelpResult->coordinates->latitude,
                $yelpResult->coordinates->longitude, "yelp");
            if ($place != null)
                $places = $places->push($place);
        }*/

        foreach ($foursquareResults->response->venues as $fsResult) {
            $place = $this->createOrUpdatePlace($fsResult,
                $fsResult->location->lat,
                $fsResult->location->lng, "fsquare");
            if ($place != null)
                $places = $places->push($place);
        }
        //There are 10 places
        // now we have to merge information from the 10 places
        // gotten from other APIS
        return $places;
    }

    public function searchByCityRakingRadius(Request $request){
        $radius = $request->input('radius');
        $curLat = $request->input('latitude');
        $curLong = $request->input('longitude');
        $city = $request->input('city');
        $ranking = $request->input('ranking');

        $cityAux = '';
        if ($city == "lisboa"){ //todo TÁ MAL
            $cityAux = "lisbon";
        }

        $places = new Collection();
        $tmpResult = Place::where('city', 'LIKE', '%' . $cityAux . '%')
            ->where('average_rating', '>=', $ranking)
            ->orderBy('average_rating', 'DESC')
            ->get();

        $result = [];
        foreach ($tmpResult as $r) {
            if ($r->getRadius($curLat, $curLong) <= $radius) {
                array_push($result, $r);
            }
        }
        if (count($result) >= 1)
            return response()->json($result);


        $yelpResults = json_decode(YelpAPIController::searchByCityNameRating($curLat, $curLong, $radius, $city));
        //$zomatoResults = ZomatoAPIController::searchByName($name);
        //$foursquareResults = FourSquareAPIController::searchByName($name);

        //Go through YELP results first
        foreach ($yelpResults->businesses as $yelpResult) {
            $place = $this->createOrUpdatePlace($yelpResult,
                $yelpResult->coordinates->latitude,
                $yelpResult->coordinates->longitude, "yelp");
            if ($place != null && $place->average_rating >= $ranking)
                $places = $places->push($place);
        }
        //There are some places
        // gotten from other APIS
        // dd($places);
        return response()->json($places->sortByDesc("average_rating"));
    }

    /**
     * Search by city
     *
     */
    function searchByCity(Request $request)
    {
        $city = $request->input('city');
        $places = new Collection();

        //go to db first
        $tmpResult = Place::where('city', 'LIKE', '%' . $city . '%')->get();
        if ($tmpResult->count() >= 1) {
            return $tmpResult;
        }

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
        $yelpResults = json_decode(YelpAPIController::searchByCity($city));

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
        //$zomatoResults = ZomatoAPIController::searchByName($name);

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
        //$foursquareResults = FourSquareAPIController::searchByName($name);

        //Go through YELP results first
        foreach ($yelpResults->businesses as $yelpResult) {
            $place = $this->createOrUpdatePlace($yelpResult,
                $yelpResult->coordinates->latitude,
                $yelpResult->coordinates->longitude, "yelp");
            if ($place != null)
                $places = $places->push($place);
        }
        //There are 10 places
        // now we have to merge information from the 10 places
        // gotten from other APIS
        return $places;
    }

    /**
     * Search by ranking
     *
     */
    function searchByRanking(Request $request)
    {
        $radius = $request->input('radius') ?? 5000;
        $curLat = $request->input('latitude');
        $curLong = $request->input('longitude');
        $ranking = $request->input('ranking');
        $places = new Collection();

        //go to db first
        $tmpResult = Place::where('average_rating', '>=', $ranking)->orderBy('average_rating', 'DESC')->get();
        $result = [];
        foreach ($tmpResult as $r) {
            if ($r->getRadius($curLat, $curLong) <= $radius) {
                array_push($result, $r);
            }
        }

        if (count($result) >= 1)
            return $result;

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
        //todo o yelp nao filtra os locais por rating
        $yelpResults = json_decode(YelpAPIController::searchByRanking($curLat, $curLong, $radius, $ranking));
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
        //$zomatoResults = ZomatoAPIController::searchByName($name);

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
        //$foursquareResults = FourSquareAPIController::searchByName($name);

        //Go through YELP results first
        foreach ($yelpResults->businesses as $yelpResult) {
            $place = $this->createOrUpdatePlace($yelpResult,
                $yelpResult->coordinates->latitude,
                $yelpResult->coordinates->longitude, "yelp");
            if ($place != null && $place->average_rating >= $ranking)
                $places = $places->push($place);
        }
        //There are some places
        // gotten from other APIS
        // dd($places);
        return response()->json($places->sortByDesc("average_rating"));
    }

    /**
     * Create or update reviews from place
     *
     * @param $id string|int Id of place
     * @param $place_id int Id of place
     * @param $provider string Name of provider
     */
    function createOrUpdateReviews($id, $place_id, $provider)
    {
        if ($provider == "yelp") {
            YelpAPIController::get_reviews($id, $place_id);
        } elseif ($provider == "fsquare"){
            FourSquareAPIController::get_reviews($id, $place_id);
        }
    }


    /**
     * Creates or Updates Place object in database
     * Default result is for radius.
     *
     * @param $apiResult object POI from API
     * @param $apiLat float Latitude of API place
     * @param $apiLong float Longitude of API place
     * @param $provider string Name of provider: "yelp","fsquare","zomato"
     * @return Place Place created or updated.
     */
    private function createOrUpdatePlace($apiResult, $apiLat, $apiLong, $provider)
    {
        $place = Place::whereBetween('latitude', [$apiLat - 0.0002, $apiLat + 0.0002])
            ->whereBetween('longitude', [$apiLong - 0.0002, $apiLong + 0.0002])->first();
        if ($place) {
            $place->name = $place->name ?? $apiResult->name;
            $place->city = $place->city ?? mb_strtolower($apiResult->location->city);
            if ($provider == "yelp") {
                $place->yelp_id = $apiResult->id;
                $place->image_url = $apiResult->image_url;
                $place->address = $place->address ?? $apiResult->location->display_address;
                $place->average_rating = $place->average_rating ?? $apiResult->rating;
                $place->latitude = $place->latitude ?? $apiResult->coordinates->latitude;
                $place->longitude = $place->longitude ?? $apiResult->coordinates->longitude;
                $place->qt_reviews = 0;
                $types = $this->parse_categories_array($apiResult->categories, "yelp");
                foreach ($types as $type) {
                    $place_type = PlaceType::where('type_id', $type)->where('place_id', $place->id)->first();
                    if (!$place_type) {
                        $place_type = new PlaceType(array('type_id' => $type, 'place_id' => $place->id));
                    }
                    $place_type->save();
                }
                $this->createOrUpdateReviews($place->yelp_id, $place->id, "yelp");
                $place->qt_reviews = $place->reviews()->get()->count();
//                    $types = $this->parse_categories_array($apiResult->cuisines, "zomato");
//                    foreach ($types as $type){
//                        $place->place_types()->save($type);
//                    }
//                    $place->reviews = $this->get_reviews($apiResult->id, "yelp");
            } else if ($provider == "fsquare") {
                $details = json_decode(FourSquareAPIController::getDetails($apiResult->id));
                $place->fsquare_id = $apiResult->id;
                if (isset($details->response->venue->bestPhoto)){
                    $place->image_url = $details->response->venue->bestPhoto->prefix.'400x225'.$details->response->venue->bestPhoto->suffix;
                } else{
                    $place->image_url = '';
                }
                if (isset($details->response->venue->rating)){
                    $place->average_rating = $details->response->venue->rating/2;
                }else{
                    $place->average_rating = 3;
                }
                $place->address = $place->address ?? $apiResult->location->address;
                $place->latitude = $place->latitude ?? $apiResult->location->lat;
                $place->longitude = $place->longitude ?? $apiResult->location->lng;
                $place->qt_reviews = 0;
                //dd($place);
                $types = $this->parse_categories_array($apiResult->categories, "fsquare");
                foreach ($types as $type) {
                    $place_type = PlaceType::where('type_id', $type)->where('place_id', $place->id)->first();
                    if (!$place_type) {
                        $place_type = new PlaceType(array('type_id' => $type, 'place_id' => $place->id));
                    }
                    $place_type->save();
                }
                $this->createOrUpdateReviews($place->fsquare_id, $place->id, "fsquare");
                $place->qt_reviews = $place->reviews()->get()->count();
                //var_dump($place);
//                } else if ($provider == "zomato") {
//                    $place->image_url = $place->image_url ?? $apiResult->featured_image;
//                    $place->address = $place->address ?? $apiResult->location->address;
//                    $place->average_rating = $place->average_rating ?? $apiResult->user_rating->average_rating;
//                    $place->latitude = $place->latitude ?? $apiResult->location->latitude;
//                    $place->longitude = $place->longitude ?? $apiResult->location->longitude;
//                    $place->types = $this->parse_categories_array($apiResult->cuisines, "zomato");
//                    $place->reviews = $this->get_reviews($apiResult->id, "yelp");
//                }
                $place->update();
            }

            return $place;
        }
        //var_dump($apiResult->location);
        if (isset($apiResult->location->city)){
            $place = new Place();
            //not same place
            $place->name = $apiResult->name ?? 'Sem nome';
            //var_dump("sim");
            $place->city = mb_strtolower($apiResult->location->city) ?? '';

            if ($provider == "yelp") {
                $place->yelp_id = $apiResult->id ?? '';
                $place->image_url = $apiResult->image_url ?? '';
                $place->address = $apiResult->location->address1 ?? '';
                $place->average_rating = $apiResult->rating ?? -1;
                $place->latitude = $apiResult->coordinates->latitude ?? -1;
                $place->longitude = $apiResult->coordinates->longitude ?? -1;
                $place->provider = "yelp";
                $place->qt_reviews = 0;
                $place->save();
                $types = $this->parse_categories_array($apiResult->categories, "yelp");
                foreach ($types as $type) {
                    $place_type = PlaceType::where('type_id', $type)->where('place_id', $place->id)->first();
                    if (!$place_type) {
                        $place_type = new PlaceType(array('type_id' => $type, 'place_id' => $place->id));
                    }
                    $place_type->save();
                }
                $this->createOrUpdateReviews($place->yelp_id, $place->id, "yelp");
                $place->qt_reviews = $place->reviews()->get()->count();
                $place->update();
//            $reviews = $this->get_reviews($apiResult->id, "yelp");
            } else if ($provider == "fsquare") {
                $details = json_decode(FourSquareAPIController::getDetails($apiResult->id));
                $place->fsquare_id = $apiResult->id ?? '';
                $place->address =  $apiResult->location->address ?? '';
                if (isset($details->response->venue->bestPhoto)){
                    $place->image_url = $details->response->venue->bestPhoto->prefix.'400x225'.$details->response->venue->bestPhoto->suffix;
                } else{
                    $place->image_url = '';
                }
                $place->latitude =  $apiResult->location->lat ?? -1;
                $place->longitude =  $apiResult->location->lng ?? -1;
                //dd($details->response->venue);
                if (isset($details->response->venue->rating)){
                    $place->average_rating = $details->response->venue->rating/2;
                }else{
                    $place->average_rating = 3;
                }
                $place->provider = "fsquare";
                $place->qt_reviews = 0;
                $place->save();
                $types = $this->parse_categories_array($apiResult->categories, "fsquare");
                foreach ($types as $type) {
                    $place_type = PlaceType::where('type_id', $type)->where('place_id', $place->id)->first();
                    if (!$place_type) {
                        $place_type = new PlaceType(array('type_id' => $type, 'place_id' => $place->id));
                    }
                    $place_type->save();
                }
                $this->createOrUpdateReviews($place->fsquare_id, $place->id, "fsquare");
                $place->qt_reviews = $place->reviews()->get()->count();
                $place->update();
            }
        }

//        else if ($provider == "fsquare") {
//            $place->address =  $apiResult->location->formattedAddress;
//            $place->latitude =  $apiResult->location->lat;
//            $place->longitude =  $apiResult->location->lng;
//            $place->types = $this->parse_categories_array($apiResult->cuisines, "fsquare");
//        } else if ($provider == "zomato") {
//            $place->image_url =  $apiResult->featured_image;
//            $place->address =  $apiResult->location->address;
//            $place->average_rating =  $apiResult->user_rating->average_rating;
//            $place->latitude =  $apiResult->location->latitude;
//            $place->longitude =  $apiResult->location->longitude;
//            $types = $this->parse_categories_array($apiResult->cuisines, "zomato");
//            foreach ($types as $type){
//                $place->place_types()->save($type);
//            }
//            $place->reviews = $this->get_reviews($apiResult->id, "yelp");
//            $place->qt_reviews = $place->reviews()->count();
//        }
        return $place;
    }


    function getReviews($place_id)
    {
        return Place::find($place_id)->reviews()->get();
    }
    /**
     * HELPERS
     */

    /**
     * Compares both latitude and longitude to the precision of 4 decimals
     * with a range of 0.0002 units upwards and downwards as margin of error
     *
     * @param $apiLat float Latitude of API place
     * @param $apiLong float Longitude of API place
     * @param $curLat float Latitude of place
     * @param $curLong float Longitude of place
     *
     * @return Boolean <strong>true</strong> if it's the same place, <strong>false</strong> if not
     */
    private function is_same_place($apiLat, $apiLong, $curLat, $curLong)
    {
        //check latitude
        $is_latitude_equal = false;
        $is_longitude_equal = false;

        if (abs($curLat - $apiLat) < 0.0002) {
            $is_latitude_equal = true;
        }
        if (abs($curLong - $apiLong) < 0.0002) {
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
     * @param $categories array|string Array of category results
     * @param $provider string Provider. Supports "yelp", "fsquare", "zomato"
     *
     * @return array of categories
     */
    private function parse_categories_array($categories, $provider)
    {
        $result = array();
        $temp = array();
        if ($provider == "yelp") {
            foreach ($categories as $category) {
                array_push($temp, mb_strtolower($category->alias));
            }
        } else if ($provider == "fsquare") {
            foreach ($categories as $category) {
                array_push($temp, mb_strtolower($category->shortName));
            }
        } else if ($provider == "zomato") {
            $temp = explode(',', $categories);
        }
        foreach ($temp as $type) {
            $t = Type::where('name', $type)->first();
            if (!$t) {
                //if theres no type, create
                $t = new Type(array('name' => $type));
                $t->save();
            }
            //if it exists only push
            array_push($result, $t->id);
        }
        return $result;
    }
}
