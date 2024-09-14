<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_retrieving_all(): void
    {
        $response = $this->get('/authors');

        $response->assertStatus(200);
    }

    public function test_retrieving_detail(): void
    {
        $id = Author::first()->id;
        $response = $this->getJson("/authors/$id");

        $response->assertStatus(200); 
    }

    public function test_create(): void
    {
        $response = $this->postJson('/authors', [
            'name' => 'pramono',
            'bio' => 'fullstack developer',
            'birth_date' => '1994-09-16',
        ]);

        $response->assertStatus(201);
           
    }

    public function test_update(): void
    {
        $id = Author::first()->id;
        
        $response = $this->putJson("/authors/$id", [
            'name' => 'pramono',
            'bio' => 'fullstack developer',
            'birth_date' => '1994-09-16',
        ]);

        $response->assertStatus(201);
    }

    public function test_delete(): void
    {
        $id = Author::doesntHave('books')->first()->id;
        $response = $this->deleteJson("/authors/$id");

        $response->assertStatus(204);

    }
}