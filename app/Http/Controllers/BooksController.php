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
        $book = Book::create($this->validateRequest());
//        return redirect('/books/'.$book->id);
        return redirect($book->path());
    }

    public function update(Book $book){

//        $data = $this->validateRequest();

//        $book->update($data);
        $book->update($this->validateRequest());
//        return redirect('/books/'.$book->id);
        return redirect($book->path());

    }

    public function destroy(Book $book) {

        $book->delete();

        return redirect('/books');

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
