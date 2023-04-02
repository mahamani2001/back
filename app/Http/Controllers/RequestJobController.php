<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\RequestJob;
use Illuminate\Http\Request;

class RequestJobController extends Controller
{
    public function getRequestJob(){

        return response()->json(RequestJob::all(),200);

    }
 
    public function addrequestjob(Request $request){
        $requestjob=RequestJob::create($request->all());
        return response()->json($requestjob,202);
    }
   
    // updateRequest
    public function updaterquestjob(Request $request,$id){
        $requestjob=RequestJob::find($id);
       if(is_null($requestjob)){
     return response()->json(['message'=>'il y a aucune demand'],404);

       }
       $requestjob->update($request->all());
       return response($requestjob,200);
    }
    
public function deleteRequestJob(Request $request,$id){
    $RequestJob=RequestJob::find($id);
   if(is_null($RequestJob)){
 return response()->json(['message'=>'il y a aucune demande pour le moment'],404);

   }
   $RequestJob->delete();
   return response("supprimer la demande ",204);
}
function search($title){
  //return Product::where("title",$title)->get();//hadhia haja précise
  return RequestJob::where("title","like","%".$title."%")->get();
}

}
