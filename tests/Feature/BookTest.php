<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_retrieving_all(): void
    {
        $response = $this->get('/books');

        $response->assertStatus(200);
    }

    public function test_retrieving_detail(): void
    {
        $id = Book::first()->id;
        $response = $this->getJson("/books/$id");

        $response->assertStatus(200); 
    }

    public function test_create(): void
    {
        $response = $this->postJson('/books', [
            'title' => 'belajar laravel dasar',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim, ullam.',
            'publish_date' => '2023-09-16',
            'author_id' => '1',
        ]);

        $response->assertStatus(201);
           
    }

    public function test_update(): void
    {
        $id = Book::first()->id;
        $response = $this->putJson("/books/$id", [
            'title' => 'belajar laravel dasar update',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim, ullam.',
            'publish_date' => '2023-09-16',
            'author_id' => '1',
        ]);

        $response->assertStatus(201);
        
    }

    public function test_delete(): void
    {
        $id = Book::first()->id;
        $response = $this->deleteJson("/books/$id");

        $response->assertStatus(200);

    }
}
