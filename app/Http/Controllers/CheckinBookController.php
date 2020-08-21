<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class CheckinBookController extends Controller
{
    /**
     * CheckinBookController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public  function store(Book $book){


        try{
            $book->checkin(auth()->user());
        } catch(\Exception $e){
            return response([],404);
        }
        // return response([],404);
        // $book->checkin(auth()->user());

    }
}
