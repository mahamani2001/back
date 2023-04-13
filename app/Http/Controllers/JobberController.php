<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class JobberController extends Controller
{
   /* public function __construct()
    {
        $this->middleware('jwt.verify');
    }*/
        public function index()
        {
            $prestataires = User::where('role', 'prestataire')->get();
            return response()->json([
                'success' => true,
                'data' => $prestataires,
            ]);
        }
        public function store(Request $request)
        {
            $validatedData = $request->validate([
                'lastname' => 'required',
                'firstname' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone'=>'required|string',
                'address' => 'required|string',
                'photo' => 'nullable',
                'competence' => 'nullable',
                'numero_cin' => 'nullable',
                'diplome' => 'nullable',
            ]);
        
            $prestataire = new User;
            $prestataire->lastname = $validatedData['lastname'];
            $prestataire->firstname = $validatedData['firstname'];
            $prestataire->email = $validatedData['email'];
            $prestataire->phone = $validatedData['phone'];
            $prestataire->address = $validatedData['address'];
            $prestataire->password = bcrypt($validatedData['password']);
            $prestataire->photo = $validatedData['photo'];
            $prestataire->competence = $validatedData['competence'];
            $prestataire->numero_cin = $validatedData['numero_cin'];
            $prestataire->diplome = $validatedData['diplome'];
            $prestataire->role = 'prestataire';
            $prestataire->save();
        
            return response()->json([
                'message' => 'Prestataire created successfully',
                'data' => $prestataire
            ], 201);
        }
        
public function show($id)
{
    $prestataire = User::where('id', $id)
                  ->where('role', 'prestataire')
                  ->firstOrFail();

    return response()->json([
        'success' => true,
        'data' => $prestataire,
    ]);
}


        public function destroy($id)
    {
        $prestataire = User::where('id', $id)
                      ->where('is_prestataire', true)
                      ->firstOrFail();
    $prestataire->delete();

    return response()->json([
        'message' => 'Prestataire deleted successfully',
        'data' => []
    ], 200);
    }
    public function update(Request $request, $id)
    {
        $prestataire = User::where('id', $id)
                      ->where('is_prestataire', true)
                      ->firstOrFail();

    // Update the prestataire attributes
    $prestataire->firstname = $request->input('firstname');
    $prestataire->lastname = $request->input('lastname');
    $prestataire->email = $request->input('email');
    $prestataire->password = bcrypt($request->input('password'));
    $prestataire->photo = $request->input('photo');
    $prestataire->competence = $request->input('competence');
    $prestataire->numero_cin = $request->input('numero_cin');
    $prestataire->diplome = $request->input('diplome');
    $prestataire->save();

    return response()->json([
        'message' => 'Prestataire updated successfully',
        'data' => $prestataire
    ], 200);
    }
}
