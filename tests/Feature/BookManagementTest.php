<?php

namespace Tests\Feature;

use App\Author;
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

        $response = $this->post('/books', $this->data());

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
            'author_id' => '',
        ]);

//        $response->assertSessionHasErrors('title');
        $response->assertSessionHasErrors('author_id');
   }

      /**@test */
      public function test_a_author_is_required(){
          $response =$this->post('books',array_merge(
              $this->data(),['author_id' => '']
          ));
          $response->assertSessionHasErrors('author_id');
      }

    /** @test*/
    public  function  test_a_book_can_be_updated(){

//        $this->withoutExceptionHandling();

         $this->post('/books',$this->data());

          $book = Book::first();

         $response = $this->patch($book->path(), [
             'title' =>'New Title',
             'author_id' => 'New Author',
         ]);

//         dd(Book::first()->author_id);

         $this->assertEquals('New Title',Book::first()->title);
         $this->assertEquals(2,Book::first()->author_id);
        // $response->assertRedirect('/books/'.$book->id);
        $response->assertRedirect($book->fresh()->path());

        $this->assertCount(2,Author::all());

    }

    /**@test*/
    public function test_a_book_can_be_deleted(){

        $this->withoutExceptionHandling();

        $this->post('/books',$this->data());

        $book = Book::first();
        $this->assertCount(1,Book::all());

//        $response = $this->delete('/books/'.$book->id);
        $response = $this->delete($book->path());

        $this->assertCount(0,Book::all());
        $response->assertRedirect('/books');
    }

    /**@test*/
    public function test_a_new_author_is_automatically_added () {

        $this->withoutExceptionHandling();

        $this->post('/books',$this->data());

        $book = Book::first();
        $author = Author::first();

//        dd($book->author_id);

        $this->assertEquals($author->id,$book->author_id);
        $this->assertCount(1,Author::all());
    }


    public function data()
    {
        return [
            'title' => 'Cool Book Title',
            'author_id' => 'Victor',
        ];
    }
}
