<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionsResource;
use App\Place;
use App\Question;
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
            return response()->json($question->id, 200);
        }

        return response()->json(['msg' => 'Could\'nt store question'], 500);
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
            if (mb_strtolower($question->place->city) == mb_strtolower(Auth::user()->local)){
                $question->isMine = 0;
                $questions->push($question);
            }
        }
        return response()->json(QuestionsResource::collection($questions));
    }
}
