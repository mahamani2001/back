<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class RequestJobControllerTest extends TestCase
{
   
    use RefreshDatabase, InteractsWithDatabase;

    public function testPrestataireCanRespondToJobRequest()
    {
        // Create a Prestataire user
        $prestataire = factory(User::class)->create(['role' => 'prestataire']);

        // Create a JobRequest with category and job
        $category = factory(Category::class)->create();
        $job = factory(Job::class)->create(['category_id' => $category->id]);

        // Create a RequestJob for the job
        $requestJob = factory(RequestJob::class)->create([
            'user_id' => $prestataire->id,
            'job_id' => $job->id,
            'category_id' => $category->id,
        ]);

        // Test acceptance of job request by Prestataire
        $response = $this->actingAs($prestataire)
                         ->post(route('request_jobs.respond', [
                             'id' => $requestJob->id,
                             'acceptance' => 'accepte',
                             'price' => 100.00,
                         ]));
        $response->assertStatus(200);

        // Assert that the offer was created for the job request
        $this->assertDatabaseHas('offres', [
            'user_id' => $prestataire->id,
            'demande_service_id' => $requestJob->id,
            'prix' => 100.00,
            'statut' => 'accepte',
        ]);
    }
}
