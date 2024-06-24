<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PointTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test point creation.
     *
     * @return void
     */
    public function test_create()
    {
        $response = $this->postJson('/api/points', ['name' => 'required, unique, robust', 'x' => 0, 'y' => 0]);
        $response->assertStatus(200)
                 ->assertJson(['id' => 1, 'name' => 'required, unique, robust', 'x' => 0, 'y' => 0]);
    }
    /**
     * Test point retrieval.
     *
     * @return void
     */
    public function test_retrieve()
    {
        $this->postJson('/api/points', ['name' => 'required, unique, robust', 'x' => 0, 'y' => 0]);
        $response = $this->get('/api/points/2');
        $response->assertStatus(200)
                 ->assertJson(['id' => 2, 'name' => 'required, unique, robust', 'x' => 0, 'y' => 0]);
                 
    }
    /**
     * Test retrieve all points.
     * 
     * @return void
     */
    public function test_retrieve_all()
    {
        $this->postJson('/api/points', ['name' => 'required', 'x' => 0, 'y' => 0]);
        $this->postJson('/api/points', ['name' => 'required, unique', 'x' => 0, 'y' => 0]);
        $this->postJson('/api/points', ['name' => 'required, unique, robust', 'x' => 0, 'y' => 0]);

        $response = $this->get('/api/points');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                    '*' => [
                        'id',
                        'name',
                        'x',
                        'y'
                    ]
                ])
                ->assertJsonCount(3);
    }    

    /**
     * Test update a point.
     */
    public function test_update()
    {
        $this->postJson('/api/points', ['name' => 'required', 'x' => 0, 'y' => 0]);
        $response = $this->putJson('/api/points/6', ['name' => 'required, unique', 'x' => 1, 'y' => 1]);
        $response->assertStatus(200)
                 ->assertJson(['id' => 6, 'name' => 'required, unique', 'x' => 1, 'y' => 1]);
        
        $this->postJson('/api/points', ['name' => 'unique', 'x' => 0, 'y' => 0]);
        $response = $this->get('/api/points');
        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJson([['id' => 6, 'name' => 'required, unique', 'x' => 1, 'y' => 1], 
                                ['id' => 7, 'name' => 'unique', 'x' => 0, 'y' => 0]]);
    }


    /**
     * Test delete a point.
     */
    public function test_delete()
    {
        $this->postJson('/api/points', ['name' => 'required', 'x' => 0, 'y' => 0]);
        $response = $this->delete('/api/points/8');
        $response->assertStatus(200)
                 ->assertJson(['message' => 'deleted']);

        $response = $this->get('/api/points');
        $response->assertStatus(200)
                 ->assertJsonCount(0);
    }

    /**
     * Test create point failure
     */
    public function test_create_fail()
    {
        $this->postJson('/api/points', ['name' => 'required', 'x' => 0, 'y' => 0]); // id 9
        $this->postJson('/api/points', ['name' => 'unique', 'x' => 0, 'y' => 0]); // id 10
        $response = $this->postJson('/api/points', []);
        $response->assertStatus(422);
        $response = $this->postJson('/api/points', ['name' => '', 'x' => 1, 'y' => 1]);
        $response->assertStatus(422);
        $response = $this->postJson('/api/points', ['name' => 'required', 'x' => 1, 'y' => 1]);
        $response->assertStatus(422);
        $response = $this->postJson('/api/points', ['name' => 'unique', 'x' => 'a', 'y' => 1]);
        $response->assertStatus(422);
        $response = $this->postJson('/api/points', ['name' => 'unique', 'x' => 1, 'y' => 'b']);
        $response->assertStatus(422);
        $response = $this->postJson('/api/points', ['name' => 'unique', 'x' => 1, 'y' => 1.5]);
        $response->assertStatus(422);
        $response = $this->postJson('/api/points', ['name' => 'rboust', 'x' => 1, 'y' => 1]); // id 11
        $response->assertStatus(200);
    }


    /**
     * Test retrieval failure
     */
    public function test_retrieve_fail()
    {
        for ($i = 1; $i <= 11; $i++) {
            $response = $this->get('/api/points/' . $i);
            $response->assertStatus(404);
        }
    }
    
    /**
     * Test update point failure
     */
    public function test_update_fail()
    {
        $this->postJson('/api/points', ['name' => 'required', 'x' => 0, 'y' => 0]); // id 12
        $this->postJson('/api/points', ['name' => 'unique', 'x' => 0, 'y' => 0]); // id 13
        $response = $this->putJson('/api/points/12', []);
        $response->assertStatus(422);
        $response = $this->putJson('/api/points/12', ['name' => '', 'x' => 1, 'y' => 1]);
        $response->assertStatus(422);
        $response = $this->putJson('/api/points/12', ['name' => 'unique', 'x' => 'a', 'y' => 1]);
        $response->assertStatus(422);
        $response = $this->putJson('/api/points/12', ['name' => 'unique', 'x' => 1, 'y' => 'b']);
        $response->assertStatus(422);
        $response = $this->putJson('/api/points/12', ['name' => 'unique', 'x' => 1, 'y' => 1.5]);
        $response->assertStatus(422);
        $response = $this->putJson('/api/points/12', ['name' => 'unique', 'x' => 1, 'y' => 1]);
        $response->assertStatus(422);
        $response = $this->putJson('/api/points/12', ['name' => 'robust', 'x' => 1, 'y' => 1]); // id 14
        $response->assertStatus(200);
    }

    /**
     * Test deletion failure
     */
    public function test_delete_fail()
    {
        for ($i = 1; $i <= 15; $i++) {
            $response = $this->delete('/api/points/' . $i);
            $response->assertStatus(404);
        }
    }
}
