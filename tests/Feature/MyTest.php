<?php

namespace Tests\Feature;

use Brick\Math\Internal\Calculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function testAdd()
{
    $calculator = new \App\Models\Calculator();
    $result = $calculator->add(2, 3);
    $this->assertEquals(5, $result);
}
}
