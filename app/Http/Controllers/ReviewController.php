<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    public function addavis(Request $request){
        $avis=Review::create($request->all());
        return response()->json($avis,202);
    }
    public function getallavis(){
     return response()->json(Review::all(),200);

    }
    public function updateavis(Request $request,$id){
        $avis=Review::find($id);
       if(is_null($avis)){
     return response()->json(['message'=>'il y a aucune demande'],404);

       }
       $avis->update($request->all());
       return response($avis,200);
    }
    public function deleteavis(Request $request,$id){
        $avis=Review::find($id);
       if(is_null($avis)){
     return response()->json(['message'=>'il y a aucune demande pour le moment'],404);
    
       }
       $avis->delete();
       return response("supprimer la demande ",204);
    }
    public function store(Request $request, $jobber_id)
    {
        $user = auth()->user();
        
        $review = new Review();
        $review->user_id = $user->id;
        $review->jobber_id = $jobber_id;
        $review->comment = $request->input('comment');
        $review->rating = $request->input('rating');
        
        $review->save();
        
        return response()->json(['message' => 'Review posted successfully'], 200);
    }
/*
public function getJobberReview(Request $request)
{
    $user = auth()->user(); // get the authenticated user
    $reviews = Review::where('jobber_id', $user->id)->get();
    return response()->json(['reviews' => $reviews]);
}*/

}