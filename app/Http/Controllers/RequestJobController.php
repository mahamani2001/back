<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\RequestJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class RequestJobController extends Controller
{
    public function getRequestJob(){

        return response()->json(RequestJob::all(),200);

    }
  //post request to all prestataire that have the same categorie_id
    public function addrequestjob(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $requestjob = new RequestJob();
        $requestjob->user_id = $user->id;
        $requestjob->category_id = $request->input('category_id');
        $requestjob->title = $request->input('title');
        $requestjob->description = $request->input('description');
        $requestjob->start_date = $request->input('start_date');
        $requestjob->end_date = $request->input('end_date');
        $requestjob->time = $request->input('time');
        $requestjob->location = $request->input('location');
        $requestjob->save();

        $requestjob = User::where('role', 'prestataire')
        ->whereHas('category', function($query) use ($request){
            $query->where('id', $request->input('category_id'));
        }) ->get();//whereHas method to filter the related models by the category ID.
      /*  foreach ($Jobber as $Jobber) {
            // Send notification code goes here
        }*/
        
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
//get les request envoyer par un client
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
//get les request envoyer par un client à un jobber particuliére
public function getProviderRequests(Request $request)
{
    // Get the authenticated user (jobber)
    $user = Auth::user();

    if ($user) {
        // Get the job requests for the authenticated provider (jobber)
        $jobRequests = RequestJob::with(['user' => function ($query) {
                $query->select('id', 'firstname');
            }, 'job', 'category'])
            ->join('users', 'request_jobs.user_id', '=', 'users.id')
            ->where('request_jobs.jobber_id', '=', $user->id) // Use $user->id instead of $jobber_id
            ->select('request_jobs.*', 'users.firstname as client_firstname')
            ->get();

        return response()->json($jobRequests);
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

//post request from un client a un jobber particuliére
public function postRequestToJobber(Request $request)
{
   
        $user = JWTAuth::parseToken()->authenticate();
        $requestjob = new RequestJob();
        $requestjob->user_id = $user->id;     
        $requestjob->jobber_id = $request->input('jobber_id');
        $requestjob->category_id = $request->input('category_id');
        $requestjob->title = $request->input('title');
        $requestjob->description = $request->input('description');
        $requestjob->start_date = $request->input('start_date');
        $requestjob->end_date = $request->input('end_date');
        $requestjob->time = $request->input('time');
        $requestjob->location = $request->input('location');
        $requestjob->save();
       
        return response()->json([
            'success' => true,
            'data' => $requestjob,
            'message' => 'Request job created successfully.'
        ]);
    
}
//
public function getClientRequest(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();
    $requestjob = RequestJob::where('user_id', $user->id)->get();
    return response()->json(['requestjob' => $requestjob]);
}

public function getJobberRequest(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();

    $requestJobs = RequestJob::with([
        'user' => function ($query) {
            $query->select('id', 'firstname');
        },
        'category'
    ])
        ->where('jobber_id', $user->id)
        ->get();

    return response()->json(['requestJobs' => $requestJobs]);
}





}
