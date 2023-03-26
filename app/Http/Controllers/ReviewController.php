<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

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
}
