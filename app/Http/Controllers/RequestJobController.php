<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\RequestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestJobController extends Controller
{
    public function getRequestJob(){

        return response()->json(RequestJob::all(),200);

    }
 
    public function addrequestjob(Request $request){
        $requestjob=RequestJob::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $requestjob,
            'message' => 'Request job created successfully.'
        ]);
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
public function index(Request $request)
{
    $jobRequests = DB::table('request_jobs')
        ->join('jobs', 'jobs.id', '=', 'request_jobs.job_id')
        ->join('categories', 'categories.id', '=', 'request_jobs.category_id')
        ->join('users', 'users.id', '=', 'request_jobs.user_id')
        ->select('request_jobs.*', 'jobs.title as job_title', 'categories.name as category_name', 'users.firstname as user_name')
        ->get();
    return response()->json($jobRequests);
}
public function getClientRequests(Request $request)
{
    $user_id = $request->query('user_id');

    $jobRequests = DB::table('request_jobs')
        ->join('jobs', 'jobs.id', '=', 'request_jobs.job_id')
        ->join('categories', 'categories.id', '=', 'request_jobs.category_id')
        ->join('users', 'users.id', '=', 'request_jobs.user_id')
        ->where('request_jobs.user_id', '=', $user_id)
        ->select('request_jobs.*', 'jobs.title as job_title', 'categories.name as category_name','users.firstname as user_name','u')
        ->get();

    return response()->json($jobRequests);
    
}

public function getProviderRequests(Request $request)
{
    // Get the provider ID from the authenticated user
    $provider_id = auth()->id();

    // Get the job requests for the authenticated provider
    $jobRequests = RequestJob::with(['user' => function ($query) {
            $query->select('id', 'firstname');
        }, 'job', 'category'])
        ->join('users', 'request_jobs.user_id', '=', 'users.id')
        ->where('request_jobs.provider_id', '=', $provider_id)
        ->select('request_jobs.*', 'users.firstname as client_firstname')
        ->get();

    return response()->json($jobRequests);
}
public function postRequestsToProvider(Request $request)
{
    $provider_id = $request->input('provider_id');
    $user_id = $request->input('user_id');
   
    $jobRequests = DB::table('request_jobs')
        ->join('jobs', 'jobs.id', '=', 'request_jobs.job_id')
        ->join('categories', 'categories.id', '=', 'request_jobs.category_id')
        ->where('request_jobs.user_id', '=', $user_id)
        ->join('users', 'request_jobs.user_id', '=', 'users.id')
        ->where('request_jobs.provider_id', '=', $provider_id) // add provider ID filter
        ->select('request_jobs.*', 'jobs.title as job_title', 'categories.name as category_name', 'users.firstname as client_firstname')
        ->get();

    return response()->json($jobRequests);
}


}
