<?php

namespace App\Http\Controllers;

use App\Models\Disponibilite;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class DisponibiliteContoller extends Controller
{
    public function index()
    {
        return Disponibilite::all();
    }
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $validatedData = $request->validate([
            'actif' => 'required|boolean',
            'heure_debut' => 'required|string|regex:/^\d{2}:\d{2}(-\d{2}:\d{2})?$/',
            'heure_fin' => 'string|regex:/^\d{2}:\d{2}$/',
            'jour' => 'required|string',
          
        
        ]);
        
        $disponibilite = new Disponibilite();
        $disponibilite->actif = $validatedData['actif'];
        $disponibilite->jour = $validatedData['jour'];
        $disponibilite->jobber_id = $user->id;
     

        
        $heures = explode('-', $validatedData['heure_debut']);
        $disponibilite->heure_debut = Carbon::createFromFormat('H:i', $heures[0]);
        if (isset($validatedData['heure_fin'])) {
            $disponibilite->heure_fin = Carbon::createFromFormat('H:i', $validatedData['heure_fin']);
        }
        
        $disponibilite->save();
        
        return response()->json([
            'message' => 'Disponibilite created',
            'disponibilite' => $disponibilite
        ], 201);
    }
    
    

    

    public function show($jobber_id)
    {
        $disponibilite = Disponibilite::where('jobber_id',$jobber_id)->get();
        return $disponibilite;
    }

    public function update(Request $request, $id)
    {
        $disponibilite = Disponibilite::find($id);
        $disponibilite->actif = $request->input('actif');
        $disponibilite->heure_debut = $request->input('heure_debut');
        $disponibilite->heure_fin = $request->input('heure_fin');
        $disponibilite->jour = $request->input('jour');
        $disponibilite->save();
        return response()->json([
            'message' => 'Disponibilite updated',
            'disponibilite' => $disponibilite
        ], 200);
    }

    public function destroy($id)
    {
        $disponibilite = Disponibilite::find($id);
        $disponibilite->delete();

        return response()->json([
            'message' => 'Disponibilite deleted'
        ], 200);
    }

public function getUserAvailability(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();
    $disponibilites = Disponibilite::where('jobber_id', $user->id)->get();
    return response()->json(['disponibilites' => $disponibilites]);
}
//get jobber review
public function getUserDisponibilite($jobber_id) {
    $disponibilites = Disponibilite::where('jobber_id', $jobber_id)->get();
    return response()->json(['reviews' => $disponibilites], 200);
}
}
