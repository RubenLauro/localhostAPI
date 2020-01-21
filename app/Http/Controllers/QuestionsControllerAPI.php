<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionsResource;
use App\Place;
use App\Question;
use App\User;
use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase;

class QuestionsControllerAPI extends Controller
{
    //
    public function store(Request $request, Place $place)
    {
        $question = new Question();
        $question->place_id = $place->id;
        $question->user_id = Auth::id();
        $question->question = $request->question;

        if ($question->save()) {
            foreach (User::where('id', '!=', Auth::id())->where('local', mb_strtolower($question->place->city))->get() as $user) {
                $data = new \stdClass();
                $data->to = $user->messaging_token;
                $data->notification = new \stdClass();
                $data->notification->title = $question->place->name;
                $data->notification->body = $question->user->first_name." ".$question->user->last_name." pediu uma recomendação";
                $data->click_action = "local";
                $data->mutable_content = true;
                $data->data = new \stdClass();
                $data->data->chat_id = $question->id;
                $data->data->place_name = $question->place->name;
                $data->data->place_lat = $question->place->latitude;
                $data->data->place_lng = $question->place->longitude;
                $data->data->questao = $question->question;
                //dd(json_encode($data));
                $http = new \GuzzleHttp\Client;
                $response = $http->post('https://fcm.googleapis.com/fcm/send', [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'key=AAAArqCMkAI:APA91bGLZ4ZgSGXBFP8cvdda3NjZQFbZImQW4lrwW9YpbkwTe99AhGJ8RO9Tw1GewjSXnJ9oYZRsKjosT7hnxle7fAO8YOzD-5HEWR9omc1zAV1NcNeOYiJl8w4lDfiM3tTTEvx5ZJ7F',
                        ]
                    ,
                    'body' => json_encode($data),
                    'exceptions' => false,
                ]);
            }
            return response()->json(new QuestionsResource($question), 200);
        }

        return response()->json(['msg' => 'Could\'nt store question'], 500);
    }

    public function delete(Question $question){
        try {
            if ($question->delete()) {
                return response()->json("Question deleted", 200);
            }
        } catch (\Exception $e) {
            return response()->json("Couldn't delete question", 500);
        }
    }

    public function getQuestions()
    {
/*
        $factory = new Firebase\Factory();
        $firestore = $factory->createFirestore();

        $db = $firestore->database();

        # [START fs_add_data_1]
        $docRef = $db->collection('users')->document('lovelace');
        $docRef->set([
            'first' => 'Ada',
            'last' => 'Lovelace',
            'born' => 1815
        ]);
        printf('Added data to the lovelace document in the users collection.' . PHP_EOL);
        # [END fs_add_data_1]
        # [START fs_add_data_2]
        $docRef = $db->collection('users')->document('aturing');
        $docRef->set([
            'first' => 'Alan',
            'middle' => 'Mathison',
            'last' => 'Turing',
            'born' => 1912
        ]);
        printf('Added data to the aturing document in the users collection.' . PHP_EOL);
        */
        //        $factory = new Firebase\Factory();
//        $firestore = $factory->createFirestore();
//        $projectId = 'localhost-2861e';
//        try {
//            $db = new FirestoreClient([
//                'projectId' => $projectId,
//            ]);
//        } catch (GoogleException $e) {
//            dd($e->getMessage());
//        }
////        # [START fs_add_data_1]
//        $docRef = $db->collection('users')->document('lovelace');
//        $docRef->set([
//            'first' => 'Ada',
//            'last' => 'Lovelace',
//            'born' => 1815
//        ]);


        $questions = Auth::user()->questions;
        foreach ($questions as $question) {
            $question->isMine = 1;
        }

        foreach (Question::where('user_id', '!=', Auth::id())->get() as $question) {
            if (mb_strtolower($question->place->city) == mb_strtolower(Auth::user()->local)) {
                $question->isMine = 0;
                $questions->push($question);
            }
        }
        //dd(QuestionsResource::collection($questions));
        return response()->json(QuestionsResource::collection($questions));
    }
}
