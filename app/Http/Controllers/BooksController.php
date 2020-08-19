<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function store(){
//       $data = $this->validateRequest();

        /*Book::create([
            'title' => request('title'),
            'author' => request('author'),
        ]);*/
//        Book::create($data);
        Book::create($this->validateRequest());
    }

    public function update(Book $book){

//        $data = $this->validateRequest();

//        $book->update($data);
        $book->update($this->validateRequest());

    }

    /**
     * @return array
     */
    public function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'author' => 'required',
        ]);
    }
}
