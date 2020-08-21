<?php

namespace Tests\Unit;

use App\Book;
use App\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
   /**@test*/
   public  function test_a_book_can_be_checked_out() {

          $book = factory(Book::class)->create();
          $user = factory(User::class)->create();

          $book->checkout($user);

          $this->assertCount(1,Reservation::all());
          $this->assertEquals($user->id, Reservation::first()->user_id);
          $this->assertEquals($book->id, Reservation::first()->book_id);
          $this->assertEquals(now(), Reservation::first()->checked_out_at);

   }

   /**@test*/
   public  function test_a_book_can_be_returned() {

       $book = factory(Book::class)->create();
       $user = factory(User::class)->create();
       $book->checkout($user);

       $book->checkin($user);

       $this->assertCount(1,Reservation::all());
       $this->assertEquals($user->id, Reservation::first()->user_id);
       $this->assertEquals($book->id, Reservation::first()->book_id);
       $this->assertNotNull(Reservation::first()->checked_in_at);
       $this->assertEquals(now(), Reservation::first()->checked_in_at);
   }

   // IF not Checked out, then exception
    /**@test*/
    public function test_if_not_checked_out_exception_is_throw(){

        $this->expectException(\Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($user);

    }

    // a user can check out a book twice
    /**@test*/
    public function test_a_user_can_check_out_a_book_twice(){

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);  // 借书
        $book->checkin($user);   // 还书

        $book->checkout($user);  // // 借书, 在这里重新产生一条记录


        $this->assertCount(2,Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_out_at);

        $book->checkin($user);  //  // 还书

        $this->assertCount(2,Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNotNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_in_at);
    }
}
