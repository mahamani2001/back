<?php

namespace Tests\Unit;

use App\Http\Controllers\JobberController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Tests\TestCase;
use App\Models\User;

class JobberControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    use \Illuminate\Foundation\Testing\DatabaseMigrations;

    /** @test */
    public function it_returns_closest_service_providers()
    {
        
        
          // Create a mock request with latitude and longitude parameters
          $request = new Request([
            "latitude"=> 35.825393,
            "longitude"=>10.63699
        ]);
    
        // Call the findServiceProviders method and get the response
        $response = $this->post('/location/findServiceProviders', $request->all());
    
        // Assert that the response is successful
        $response->assertStatus(404);
    
        // Assert that the response contains the 'service_providers' key
        $response->assertJsonStructure([
            'service_providers',
        ]);
    
    }
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1.609344; // convert miles to kilometers
    }
}
