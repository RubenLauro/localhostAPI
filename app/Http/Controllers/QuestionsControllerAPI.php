<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionsResource;
use App\Place;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

use Kreait\Firebase;

class QuestionsControllerAPI extends Controller
{
    //
    public function store (Request $request, Place $place){
        $question = new Question();
        $question->place_id = $place->id;
        $question->user_id = Auth::id();
        $question->question = $request->question;

        if ($question->save()){
            return response()->json(['msg' => 'Question stored'], 200);
        }

        return response()->json(['msg' => 'Could\'nt store question'], 500);
    }

    public function getQuestions(){
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
