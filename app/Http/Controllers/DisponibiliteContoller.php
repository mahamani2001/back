<?php

namespace App\Http\Controllers;

use App\Models\Disponibilite;
use Illuminate\Http\Request;

class DisponibiliteContoller extends Controller
{
    public function index()
    {
        return Disponibilite::all();
    }

    public function store(Request $request)
    {
        $disponibilite = new Disponibilite();
        $disponibilite->actif = $request->input('actif');
        $disponibilite->heure = $request->input('heure');
        $disponibilite->jour = $request->input('jour');
        $disponibilite->save();

        return response()->json([
            'message' => 'Disponibilite created',
            'disponibilite' => $disponibilite
        ], 201);
    }

    public function show($id)
    {
        $disponibilite = Disponibilite::find($id);
        return $disponibilite;
    }

    public function update(Request $request, $id)
    {
        $disponibilite = Disponibilite::find($id);
        $disponibilite->actif = $request->input('actif');
        $disponibilite->heure = $request->input('heure');
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
}
