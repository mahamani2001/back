<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Offre;
use App\Models\RequestJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
        {
            $jobs = Job::all();
            return response()->json($jobs);
        }

        public function show(Job $job)
        {
            return response()->json($job);
        }
       public function  get($id){
        $job = Job::with('category')->find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }
        return response()->json($job);
    }
       
    
    public function store(Request $request)
    {
        $job = new Job();
        $job->title = $request->input('title');
        $job->description = $request->input('description');
        $job->price_max = $request->input('price_max');
        $job->price_min = $request->input('price_min');
        $job->pictureUrl = $request->input('pictureUrl');
        $job->save();
        return response()->json($job, 201);
    }

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $job->title = $request->input('title');
        $job->description = $request->input('description');
        $job->price_max = $request->input('price_max');
        $job->price_min = $request->input('price_min');
        $job->pictureUrl = $request->input('pictureUrl');
        $job->save();
        return response()->json($job, 200);
    }

    
        public function destroy(Job $job)
        {
            $job->delete();
            return response()->json(null, 204);
        }

  

}
