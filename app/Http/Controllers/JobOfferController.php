<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JobOfferController extends Controller
{
  /*  public function __construct()
    {
        $this->middleware('auth:api');
    }
*/

//en tant que prestataire je crée mon offre  à un client 
    public function create(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'response' => 'required|in:accepte,refuse',
            'prix' => 'required_if:response,accepte|numeric',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = JWTAuth::parseToken()->authenticate();
      
        $offre = new Offre();
        $offre->jobber_id = $user->id;
        $offre->user_id = $id;//Auth::id()
        $offre->demande_service_id = $id;
        $offre->prix = $request->prix;
        $offre->statut = $request->response;

        $offre->save();

        return response()->json(['message' => 'Offer created successfully'], 201);
    }
    

}
