<?php

namespace Tests\Feature;

use App\Book;
use App\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

   /**@test*/
   public function test_a_book_can_be_checkeout_by_a_signed_in_user() {

       $this->withoutExceptionHandling();

       $book = factory(Book::class)->create();


       $this->actingAs($user = factory(User::class)->create())
            ->post('/checkout/'.$book->id);


       $this->assertCount(1,Reservation::all());
       $this->assertEquals($user->id, Reservation::first()->user_id);
       $this->assertEquals($book->id, Reservation::first()->book_id);
       $this->assertEquals(now(), Reservation::first()->checked_out_at);
   }

   /**@test */
   public function  test_only_signed_in_users_can_checkout_a_book(){

//       $this->withoutExceptionHandling();

       $book = factory(Book::class)->create();

       $this->post('/checkout/'.$book->id)
            ->assertRedirect('/login');

       $this->assertCount(0,Reservation::all());

   }

    /**@test */
    public function test_only_real_books_can_be_checked_out(){

        $this->actingAs($user = factory(User::class)->create())
            ->post('/checkout/123')
            ->assertStatus(404);

        $this->assertCount(0,Reservation::all());

    }

    public function test_a_books_can_be_checked_in_by_a_signed_in_user(){

        $this->withoutExceptionHandling();

        //  set up for the test  //  create reservation record
            $book = factory(Book::class)->create();
            $user = factory(User::class)->create();
            $this->actingAs( $user)
                ->post('/checkout/'.$book->id);

            // 推荐使用 上面的 end point

           /* Reservation::create([
                'checked_in_at' => now()->subDays(),
                'random_field' => '',  // 有可能加入 随机数据，也发现不了错误
                'user_id' => $user->id,
            ]);*/
        //  set up for the test

        $this->actingAs( $user)
            ->post('/checkin/'.$book->id);


        $this->assertCount(1,Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /**@test */
    public function  test_only_signed_in_users_can_checkin_a_book(){

//       $this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $this->actingAs(factory(User::class)->create())
            ->post('/checkout/'.$book->id);

        Auth::logout();

        $this->post('/checkin/'.$book->id)
            ->assertRedirect('/login');

        $this->assertCount(1,Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);

    }

    /**@test */
    public function  test_a_404_is_thrown_if_a_book_is_not_checkout_first(){

        $this->withoutExceptionHandling();

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();


        $this->actingAs( $user)
            ->post('/checkin/'.$book->id)
            ->assertStatus(404);

        $this->assertCount(0,Reservation::all());


    }

}
