<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_get_external_book()
    {
        $response = $this->json('GET', '/api/external-books', ['name'=>'A Game of Thrones']);
        $response->assertStatus(200);
    }

    public function test_create_book()
    {
        $response = $this->json('POST', '/api/v1/books', ['name'=>'test bookw', 'isbn'=>'123-3213243567w', 'country'=>'Nigeria', 'number_of_pages'=>'50',  'authors'=>['tolu', 'isaac'], 'publisher'=>'Test Book', 'release_date'=>'2019-09-09']);
        $response->assertStatus(201);
    }
    
    public function test_list_books(){
        $response = $this->get('/api/v1/books');
        $response->assertStatus(200);
    }
    
    public function test_update_books(){
        $response = $this->patch('/api/v1/books/2', ['name'=>'Updated through test']);
        $response->assertStatus(200);
    }
    
    public function test_delete_books(){
        $response = $this->delete('/api/v1/books/2');
        $response->assertStatus(200);
    }
    
    public function test_show_books(){
        $response = $this->get('/api/v1/books/3');
        $response->assertStatus(200);
    }
}
