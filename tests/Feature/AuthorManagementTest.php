<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDataBase;
    /**@test*/
    public function test_an_author_can_be_created(){

        $this->withoutExceptionHandling();

        $this->post('/author',[
             'name' => 'Author name',
             'dob' => '05/14/1988',
        ]);

        $author = Author::all();

        $this->assertCount(1,$author);
        $this->assertInstanceOf(Carbon::class,$author->first()->dob);
        $this->assertEquals('1988/14/05',$author->first()->dob->format('Y/d/m'));
    }
}
