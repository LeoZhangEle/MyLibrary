<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
/*    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    /** @test */
    public function test_a_book_can_be_added_to_the_library() {

//        $this->withoutExceptionHandling();

        $response = $this->post('/books',[
           'title'=> 'Cool Book Title',
           'author' => 'Victor',
        ]);

        $book = Book::first();

//        $response->assertOk();
        $this->assertCount(1,Book::all());
//        $response->assertRedirect('/books/'.$book->id);
        $response->assertRedirect($book->path());

    }

    /* @test*/

    public function test_a_title_is_required() {

//      $this->withoutExceptionHandling();

        $response = $this->post('/books',[
            'title'=> 'Cool Title',
            'author' => '',
        ]);

//        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('author');
   }

    /** @test*/
    public  function  test_a_book_can_be_updated(){

//        $this->withoutExceptionHandling();

         $this->post('/books',[
            'title'=> 'Cool Title',
            'author' => 'Victor',
        ]);

          $book = Book::first();

         $response = $this->patch($book->path(), [
             'title' =>'New Title',
             'author' => 'New Author',
         ]);

         $this->assertEquals('New Title',Book::first()->title);
         $this->assertEquals('New Author',Book::first()->author);
        // $response->assertRedirect('/books/'.$book->id);
        $response->assertRedirect($book->fresh()->path());

    }

    /**@test*/
    public function test_a_book_can_be_deleted(){

        $this->withoutExceptionHandling();

        $this->post('/books',[
            'title'=> 'Cool Title',
            'author' => 'Victor',
        ]);

        $book = Book::first();
        $this->assertCount(1,Book::all());

//        $response = $this->delete('/books/'.$book->id);
        $response = $this->delete($book->path());

        $this->assertCount(0,Book::all());
        $response->assertRedirect('/books');

    }
}
