<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
                'competence' => 'nullable',
                'numero_cin' => 'nullable',
                'diplome' => 'nullable',
                'photo' => 'required|image|max:2048',
            ]);

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('.\public\images', $filename);
       
            $prestataire = new User;
            $prestataire->lastname = $validatedData['lastname'];
            $prestataire->firstname = $validatedData['firstname'];
            $prestataire->email = $validatedData['email'];
            $prestataire->phone = $validatedData['phone'];
            $prestataire->address = $validatedData['address'];
            $prestataire->password = bcrypt($validatedData['password']);
            $prestataire->competence = $validatedData['competence'];
            $prestataire->numero_cin = $validatedData['numero_cin'];
            $prestataire->diplome = $validatedData['diplome'];
            $prestataire->photo = $filename;
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


    //localisation 
  
    public function findServiceProviders(Request $request)
    {
        // get user location information from the request
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
    
        // set the search radius to 5km
        $radius = 5;
    
        // get all service providers within the search radius
        $serviceProviders = User::where('role', 'prestataire')->withinRadius($latitude, $longitude, $radius)->get();
    
        // call Thunderforest API to get the distance and duration to each service provider
        $baseUrl = 'https://tile.thunderforest.com';
        $apiKey = '42b14628e94940fb8ef24ededa5153e1';
        foreach ($serviceProviders as $provider) {
            $url = "$baseUrl/transportation/$longitude,$latitude;{$provider->longitude},{$provider->latitude}.json?key=$apiKey";
            $response = Http::get($url);
            $data = $response->json();
        
            if (isset($data['routes']) && count($data['routes']) > 0) {
                $route = $data['routes'][0];
                if (isset($route['distance']) && isset($route['duration'])) {
                    $distance = $route['distance'];
                    $duration = $route['duration'];
                    $provider->distance = $distance;
                    $provider->duration = $duration;
                } else {
                    // handle the case where distance or duration is not set
                }
            } else {
                // handle the case where routes is not set or has no elements
            }
        }
        
        // sort service providers by distance
        $serviceProviders = $serviceProviders->sortBy('distance');
    
        // return the closest service providers along with distance and duration
        return response()->json(['service_providers' => $serviceProviders]);
    }
    
}
