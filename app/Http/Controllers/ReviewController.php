<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    
    public function getallreviews(){
     return response()->json(Review::all(),200);

    }
    //poster un avis 
    public function store(Request $request, $jobber_id)
{
    $client = auth()->user();

    // Check if the authenticated user is a client
    if ($client->role !== 'client') {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Check if the jobber exists
    $jobber = User::find($jobber_id);
    if (!$jobber) {
        return response()->json(['message' => 'jobber not found'], 404);
    }

    // Create the review for the jobber
    $review = new Review();
    $review->user_id = $client->id;
    $review->jobber_id = $jobber->id;
   // $review->reviewable_type = get_class($jobber);
    $review->comment = $request->input('comment');
    $review->rating = $request->input('rating'); 
    $review->save();
    return response()->json(['message' => 'Review posted successfully'], 200);
}

//get jobber review
public function getJobberReviews($jobber_id) {
    $reviews = Review::where('jobber_id', $jobber_id)->get();
    return response()->json(['reviews' => $reviews], 200);
}


}