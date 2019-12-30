<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionsResource;
use App\Place;
use App\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getMyQuestions(){
        return response()->json(QuestionsResource::collection(Auth::user()->questions));
    }
}
